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



# vlist를 이용해서 wardict, warval을 생성해서 리턴
def setwardict(vdict):
    wardict = []
    warval = []

    data = OrderedDict()
    tmp = []
    # print(type(vlist[0]))
    # print(type(vdict[0]))
    
    for i in vdict:
        tmp.append([str(i["url"]), str(i["war"])])

    tmp = list(reversed(tmp))
    wardict = [tmp[0]]

    for idx, i in enumerate(tmp):
        for edx, j in enumerate(wardict):
            if i[0] == j[0]:
                #print("중복확인")
                break
            elif edx+1 == len(wardict):
                #print("insert")
                wardict.append(i)
                break
            else:
                pass
                # print("pass")

    # wardict에서 warval(low or high) 추출
    for i in wardict:
        warval.append(i[1])

    high = warval.count("high")
    low = warval.count("low")

    return wardict, warval




# slist에는 low 취약점이 존재하지않는 사이트리스트가 있고 wardict안에 중복되는 주소를 삭제
# 해서 low, high에 모두 안전한 사이트를 저장함
def setslist(wardict):
    global slist

    for i in wardict:
        if i[0] in slist:
            slist.remove(i[0])

# slist를 json dump로 변경해서 리턴
def getslistJSON(slist):
    sjson = []

    data = OrderedDict()
    for i in slist:
        data["url"] = i
        sjson.append(json.dumps(data, ensure_ascii = False, indent = "\t"))

    return sjson


# wardict 를 json dump로 변경해서 리턴
def getwardictJSON(wardict):
    warlist = []
    data = OrderedDict()
    alen = int(len(wardict))

    for i in wardict:
        data["url"] = i[0]
        data["war"] = i[1]
        warlist.append(json.dumps(data, ensure_ascii = False, indent = "\t"))
            
    return warlist




#- 검사한 페이지 수(O)
#- 취약점이 발견된 페이지 수(json에서 처리하기로 했었음)(O)
#- 총 취약점 개수(O)
#- 고위험 취약점 개수(o)
#- 저위험 취약점 개수(o)
#- 각 페이지 별 공격가능한 폼 개수 & 취약점 발견된 폼 개수(막대그래프)
#- 각 페이지이름, 취약점 발견된 폼 이름, 공격 쿼리(테이블), 위험도(O)

def makeResult():

    data = OrderedDict()

    wardict = [] # vdict에서 url, war키값만 추출하여 각 주소의 중복을 제거한 이중 리스트
    warval = [] # wardict에서 war키값만 추출한 리스트
    wardict, warval = setwardict(vdict)# vdict 를 이용해서 wardict, warvar를 초기화 한다.

    # slist를 wardict를 참조하여 중복을 제거   
    setslist(wardict)

    # 검사한 페이지 수 //slistlen(안전한 페이지) + len(vlist) (취약한 페이지)
    data['alistlen'] = len(wardict) + len(slist)

    # 취약한 페이지 수, high, low 
    data['vpagelen'] = len(wardict)
    data['high'] = warval.count("high") 
    data['low']  = warval.count("low")


    # vlist = 발견 페이지(페이지 URL, 파라미터 이름, 공격 쿼리, 위험도)
    data['vlistlen'] = len(vlist)
    data['vlist'] = vlist

    # makeslist = 일반 리스트인 slist를 json dump로 변경하여 반환
    data['slistlen'] = len(slist)
    data['slist'] = getslistJSON(slist)

    # makeslist = 일반 딕셔너리인 wardict를 json dump로 변경하여 반환
    data['warlistlen'] = len(wardict)
    data['warlist'] = getwardictJSON(wardict)

    #디버깅용
    #showlistinfo(vdict, getwardictJSON(wardict), getslistJSON(slist), vlist)

    res = json.dumps(data,ensure_ascii=False,indent="\t")
    res = res.replace("\n",'')
    return res

def getJSON(href,fname,query,war):
    data = OrderedDict()
    data["url"] = href.baseurl + href.url
    data["fname"] = fname
    data["query"] = query
    data["war"] = war
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
                vlist.append(getJSON(href,s.name,q,"high"))
                vdict.append({"url" : href.baseurl + href.url, "fname":s.name, "query":q, "war":"high"})
            else :  
                slistlen += 1

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
            vlist.append(getJSON(href,s.name,q,"high"))
            vdict.append({"url" : href.baseurl + href.url, "fname":s.name, "query":q, "war":"high"})
        else :  
            slistlen += 1




# 쿼리내에서 operater(연산자) 동작을 확인하여 low(저위험)페이지 판별
# href = hreflist, get method의 argument 에 1(base val)과 2(base val +1) -1 을 진행.

def checkVOper(href): #check operater is worked in query
    global slistlen
    for s in href.arglist :
        if(s.atype == "digit"):
            cq = str(( int(s.oval)+1)) + '-1'
            res1 = href.classmember(s.oval)
            res2 = href.classmember(cq)    
            # res1, res2의 결과값이 같다면 operator 동작으로 low 취약점 리스트에 세팅.
            if checkResSame(res1,res2) : 
                vlist.append(getJSON(href,s.name,cq,"low"))
                vdict.append({"url" : href.baseurl + href.url, "fname":s.name, "query":cq,
                    "war":"low"})
            else : 
                # slist에 low취약점에 안전한 사이트들을 저장
                slist.append(str(href.baseurl + href.url))
                slistlen += 1

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
