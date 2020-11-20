import crawling as cr
from collections import OrderedDict
import json
import aQlist

vlist = [] 
vdict = [] # vlist 안의 값 타입이 dict가 아니라 참조가불가능해서 따로만듬

slist = [] # secure, 안전한 사이트 리스트( append is (if warlist in clist))
slistlen = 0

# show list(list 출력, 디버깅용)
def showlistinfo(vdict, wardict, slist, vlist):
    print("총 검사 사이트")
    for i in vdict:
        print(i)

    print("취약한 사이트 목록")
    for i in wardict:
        print(i)

    print("안전한 사이트 목록")
    for i in slist:
        print(i)

    print("vlist")
    for i in vlist:
        print(i)


#- 검사한 페이지 수(O)
#- 취약점이 발견된 페이지 수(json에서 처리하기로 했었음)(O)
#- 총 취약점 개수(O)
#- 고위험 취약점 개수(o)
#- 저위험 취약점 개수(o)
#- 각 페이지 별 공격가능한 폼 개수 & 취약점 발견된 폼 개수(막대그래프)
#- 각 페이지이름, 취약점 발견된 폼 이름, 공격 쿼리(테이블), 위험도(O)

def makeResult(page):
    data = OrderedDict()
    data['spagelen'] = 0
    data['vpagelen'] = 0
    data['aparlen'] = 0
    data['vparlen'] = 0
    data['low'] = 0
    data['high'] = 0
    vpage = []

    for h in page.hreflist : 
        tmp = OrderedDict()
        tmp['url'] = h.url
        tmp['safe'] = []
        tmp['high'] = []
        tmp['low'] = []
        if (h.vul) != "safe" : 
            data['vpagelen'] += 1 
            # print(h.url)
            for a in h.arglist: 
                data['aparlen'] += 1
                if a.vul != "safe" : 
                    data['vparlen'] += 1
                    if a.vul == "high" : 
                        tmp['high'].append(a.name)
                        data['high'] += 1
                    else :
                        tmp['low'].append(a.name)
                        data['low'] += 1
                else : 
                    tmp['safe'].append(a.name)
        else : 
            for a in h.arglist: 
                data['aparlen'] += 1
                tmp['safe'].append(a.name)
            data['spagelen'] += 1  
            
        vpage.append(json.dumps(tmp,ensure_ascii=False,indent="\t"))

    data['apagelen'] = data['spagelen'] + data['vpagelen'] 
    data['sparlen'] = data['aparlen'] - data['vparlen'] 
    data['vlist'] = vlist
    data['vpage'] = vpage
    
    res = json.dumps(data,ensure_ascii=False,indent="\t")
    # print(data)
    # print(vlist)
    return res

def getJSON(href,fname,query,war,method):
    data = OrderedDict()
    data["url"] = href.baseurl + href.url
    data["fname"] = fname
    data["query"] = query
    data["war"] = war
    data['method'] = method
    return json.dumps(data,ensure_ascii=False,indent="\t")

# aQlist(SQL Cheat list) 주입 하여 high(고위험)페이지 판별
def checkSQLi2(href): #find SQL injection
    global slistlen
    
    for q in aQlist.qlist : 
        retlist = checkNormal(href,q)
        reslist = []

        for s in href.arglist : 
            for i in retlist : 
                for j in retlist : 
                    if i!=j : 
                        if checkResSame(i,j):
                            reslist.append(i)
                            reslist.append(j)
            reslist = list(set(reslist))
            # print(reslist)


            if len(reslist) >= 2 : 
                s.vul = "high"
                href.vul = "high" 
                vlist.append(getJSON(href,s.name,q,"high",href.method))
                #vlist.append(getJSON(href,s.name,q,"high"))
                #vdict.append({"url" : href.baseurl + href.url, "fname":s.name, "query":q, "war":"high"})
            else :  
                slistlen += 1
    print(len(href.arglist))

def checkSQLi(href): #find SQL injection
    global slistlen
    #for q in aQlist : 
    q = " or 1=1"
    retlist = checkNormal(href,q)
    reslist = []

    for s in href.arglist : 
        for i in retlist : 
            for j in retlist : 
                if i!=j : 
                    if checkResSame(i,j):
                        reslist.append(i)
                        reslist.append(j)
        reslist = list(set(reslist))
        if len(reslist) >= 2 : 
            pass
            #vlist.append(getJSON(href,s.name,q,"high"))
            #vdict.append({"url" : href.baseurl + href.url, "fname":s.name, "query":q, "war":"high"})
        else :  
            slistlen += 1

# 쿼리내에서 operater(연산자) 동작을 확인하여 low(저위험)페이지 판별
# href = hreflist, get method의 argument 에 1(base val)과 2(base val +1) -1 을 진행.

def checkVOper(href): #check operater is worked in query
    global slistlen
    count = 0
    for s in href.arglist :
        count += 1
        if(s.atype == "digit"):
            cq = str(( int(s.oval)+1)) + '-1'
            res1 = href.classmember(s.oval)
            res2 = href.classmember(cq)    
            # res1, res2의 결과값이 같다면 operator 동작으로 low 취약점 리스트에 세팅.
            if checkResSame(res1,res2) :
                s.vul = "low"
                href.vul = "low" 
                vlist.append(getJSON(href,s.name,cq,"low",href.method))
                #vdict.append({"url" : href.baseurl + href.url, "fname":s.name, "query":cq,"war":"low"})
            else : 
                # slist에 안전한 사이트들을 저장
                slist.append(str(href.baseurl + href.url))
                slistlen += 1
    # href.getdata()
    print(count)

def checkNormal(href,q):
    an,nl = makeAnormal(href)
    retlist = []
    for s in nl : 
        # print("query : "+s+q)
        res = href.classmember(s+q)
        # compare with anormal result.
        if checkResSame(an,res) : #this result is anormal 
            pass
        else :  #this result is normal 
            if checkError(res) : 
                retlist.append(res)
    return retlist
        
def makeAnormal(href) : #find anormal result
    qlist = ['1','2','3','4','5','a','b','c','d','-1','a1','1a']
    neq = [] #non-error querys 
    an = [] #anormally returns
    nl = [] #normal querys    
    
    for q in qlist :
        res = href.classmember(q)
        if checkError(res) :
            neq.append(res)
        
    rlen = len(neq) #normal query

    for i in range(0,rlen) :
        for j in range(0,rlen) :
            if i != j : 
                if checkResSame(neq[i],neq[j]) : 
                    an.append(neq[i])
                    an.append(neq[j])
                
    an = list(set(an))    
    for s in neq: 
        nl.append(s[2])
    for s in an:
        nl.remove(s[2])   

    return an[0],nl


# 두 페이지를 비교하여 결과값이 같다면 True 를 리턴한다.
def checkResSame(res1,res2): #check res1 and res2 request result is same
    # res1 = href.classmember(q1)
    # res2 = href.classmember(q2)    
    q1 = res1[2]
    q2 = res2[2]

    if (res1[1] != res2[1])  : # two results return code is not same
        return False
    elif not (checkError(res1) and checkError(res2)) : 
        return False

    if len(q1) > len(q2): #check if string has includes the other one.
        q3 = q1.replace(q2,'')
    else : 
        q3 = q2.replace(q1,'')

    res1_ = res1[0].replace(q1,'')
    res2_ = res2[0].replace(q2,'')

    res = cr.show_diff(res1_,res2_)
    
    for s in res[1] : #s is differ parts
        if ((q1 != s ) and (q2 != s )  and (q3 != s )) : # two results are not same 
            return False
    # two results are same 
    return True 


def checkError(res): #check return code is 200 , sql error has not acquired
    error = ['Warning','mysql','error']
       
    if res[1] == 200 :
        if any(word in res[0] for word in error ) :
            return False 
        else :  #error was not acquired
            return True
    else :
        return False
