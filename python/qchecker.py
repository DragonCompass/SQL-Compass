import crawling as cr
from collections import OrderedDict
import json
import aQlist

vlist = []
slist = []
slistlen = 0
elist = []

def makeResult():
    data = OrderedDict()
    data['alistlen'] = slistlen+len(vlist)
    data['vlistlen'] = len(vlist)
    data['vlist'] = vlist
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

def checkSQLi2(href): #find SQL injection
    global slistlen
    for q in aQlist.qlist : 
        retlist = checkNormal(href,q)
        reslist = []
        # print(retlist)
        if "hrefset" in str(type(href)) :
            for s in href.arglist : 
                for i in retlist : 
                    for j in retlist : 
                        if i!=j : 
                            if checkResSame(i,j):
                                reslist.append(i)
                                reslist.append(j)
                reslist = list(set(reslist))
        elif "formset" in str(type(href)) :
            for i in retlist : 
                print(i)    
                for j in retlist : 
                    if i!=j : 
                        if checkResSame(i,j):
                            reslist.append(i)
                            reslist.append(j)
            reslist = list(set(reslist))
        
        if len(reslist) >= 2 : 
            s.vul = "high"
            href.vul = "high" 
            vlist.append(getJSON(href,s.name,q,"high",href.method))
            #vlist.append(getJSON(href,s.name,q,"high"))
            #vdict.append({"url" : href.baseurl + href.url, "fname":s.name, "query":q, "war":"high"})
        else :  
            slistlen += 1
    # print(reslist)
    # print(str(type(href)))
    if "hrefset" in str(type(href))  :
        print(len(href.arglist))
    elif "formset" in str(type(href))  :
        print(len(href.namelist))

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
        else :  
            slistlen += 1

def checkVOper(href): #check operater is worked in query
    global slistlen
    for s in href.arglist :
        if(s.atype == "digit"):
            cq = str(( int(s.oval)+1)) + '-1'
            res1 = href.dosqli(s.oval)
            res2 = href.dosqli(cq)    
            # res1, res2의 결과값이 같다면 operator 동작으로 low 취약점 리스트에 세팅.
            if checkResSame(res1,res2) :
                s.vul = "low"
                href.vul = "low" 
                vlist.append(getJSON(href,s.name,cq,"low",href.method))
                #vdict.append({"url" : href.baseurl + href.url, "fname":s.name, "query":cq,"war":"low"})
            else : 
                slistlen += 1

def checkNormal(href,q):
    an,nl = makeAnormal(href)
    print(nl)
    retlist = []
    for s in nl : 
        res = href.dosqli(s+q)
        # compare with anormal result.
        if checkResSame(an,res) : #this result is anormal 
            pass
        else :  #this result is normal 
            if checkError(res) : 
                retlist.append(res)
    return retlist
        
def makeAnormal(href) : #find anormal result
    qlist = ['1','2','3','a','b','c','0','-1','-2']
    neq = [] #non-error querys 
    an = [] #anormally returns
    nl = [] #normal querys    
    
    for q in qlist :
        res = href.dosqli(q)
        # print(res)
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
    if (len(an) == 0) :
        an.append(" ")
    if (len(nl) == 0) :
        nl.append(" ")
    return an[0],nl

def checkResSame(res1,res2): #check res1 and res2 request result is same
    # res1 = href.dosqli(q1)
    # res2 = href.dosqli(q2)    
    q1 = res1[2]
    q2 = res2[2]

    if (res1[1] != res2[1])  : # two results return code is not same
        return False
    elif not (checkError(res1) and checkError(res1)) : 
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
