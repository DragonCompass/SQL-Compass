import crawling as cr
import qchecker as qc
import glob
import chardet
import requests
import argparse
import json

def logo():
    print("\n")
    print("           _____ ____    __         __________  __  _______  ___   _____")
    print("          / ___// __ \  / /        / ____/ __ \/  |/  / __ \/   | / ___/")
    print("          \__ \/ / / / / /  ______/ /   / / / / /|_/ / /_/ / /| | \__ \\")
    print("         ___/ / /_/ / / /__/_____/ /___/ /_/ / /  / / ____/ ___ |___/ /")
    print("        /____/\___\_\/_____/     \____/\____/_/  /_/_/   /_/  |_/____/")
    print ("                     SQL Injection Automation Tool V.0.01")
    print ("                          by KSJ 5th /Team Dragon Compass\n\n\n") 

def makePageClass(baseurl):
    flist = []
    path = 'class/*.flist.class'
    for filename in glob.glob(path):
        with open(filename) as json_file :
            tmpf = json.load(json_file)
            tmpfs = cr.formset(tmpf['url'],tmpf['name'][0],tmpf['ftype'][0],tmpf['method'])
            for i in range(1,len(tmpf['name'])):
                tmpfs.addform(tmpf['name'][i],tmpf['ftype'][i])
            # tmpfs.showdata()
            flist.append(tmpfs)
    
    hlist = []
    path = 'class/*.hreflist.class'
    for filename in glob.glob(path):
        with open(filename) as json_file :
            data = json.load(json_file)
            tmp = makeHrefClass(data,baseurl)
            # print(filename)
            # print(tmp.vul)
            hlist.append(tmp)
    
    page1 = cr.pageset(baseurl,flist,hlist)
    # page1.showdata() 
    vlist =[]
    f = open("class/vlist","r")
    while True:
        line = f.readline()
        if not line: break
        vlist.append(json.loads(line))

    qc.vlist = vlist
    print(qc.makeResult(page1))
    
    # return page1
        

def makeHrefClass(hdata,baseurl):
    tmphref = cr.hrefset(hdata['url'],baseurl,vul=hdata['vul'])
    tmphref.method = hdata['method']
    tmp = hdata['flist']
    if len(tmp) > 0 :
        for a in tmp:
            tmpf = json.loads(a)
            tmpfs = cr.formset(tmpf['url'],tmpf['name'][0],tmpf['ftype'][0],tmpf['method'])
            for i in range(1,len(tmpf['name'])):
                tmpfs.addform(tmpf['name'][i],tmpf['type'][i])
            tmphref.formlist.append(tmpfs)
        
    tmp = hdata['alist']
    if len(tmp) > 0 : 
        for a in tmp:
            tmpa = json.loads(a)
            tmphref.arglist.append(cr.argset(tmpa['atype'],tmpa['oval'],name=tmpa['name'],vul=tmpa['vul']))
    return tmphref

def getClass(ltype):
    if ltype == "flist" : 
        tmpfs = ""
        with open('class/classtmp') as json_file:
            data = json.load(json_file)
            # print(data)
            for s in data['flist']:
                tmpf = json.loads(s)
                tmpfs = cr.formset(tmpf['url'],tmpf['name'][0],tmpf['ftype'][0],tmpf['method'])
                for i in range(1,len(tmpf['name'])):
                    tmpfs.addform(tmpf['name'][i],tmpf['ftype'][i])
                # tmpfs.showdata()
        return tmpfs

    elif ltype == "hreflist" :
        with open('class/classtmp') as json_file:
            res = []
            
            data = json.load(json_file)
            baseurl = data['URL'].replace("https://","")
            baseurl = baseurl.replace("http://","")
            for s in data['hreflist'] : 
               hdata = json.loads(s)
               tmphref = makeHrefClass(hdata,baseurl)
               res.append(tmphref)
        return res

def savecwl(url):
    page1 = cr.getinfo(url)
    res = page1.getdata()
    tres = json.loads(res)
    
    print (tres["pcount"]) #show parameter count * 2

    f = open("class/classtmp","w+")
    f.write(res)
    f.close()

    return page1

if __name__=="__main__":

    parser = argparse.ArgumentParser(description= "Echo client -p port -i host -s string")
    parser.add_argument('-u', help="URL", required=False)
    parser.add_argument('-t', help="dept", required=False)
    parser.add_argument('-m', help="Mode \"result\"", required=False)
    parser.add_argument('-s', help="input_string", nargs='+', required=False)
    parser.add_argument('-pl', help="Progress List type \"flist\", \"hreflist\" ]", required=False)
    parser.add_argument('-pt', help="Progress Check type \"voper\", \"sqli\" ", required=False)
    parser.add_argument('-pi', help="Progress index", required=False)
    
    args = parser.parse_args()

    # baseurl1 = "http://compass.ton80.net/test/gnu5/bbs/board.php?bo_table=free&wr_id=5"
    # baseurl1 = "http://pingu6615.phps.kr/ksj/"
    # baseurl1 = "http://mentoring.ton80.net/"
    baseurl1 = "http://compass.ton80.net/test/part3/index.php"

    if args.u:
        baseurl1 = args.u
    if args.m == "result":
        # print("show result")
        makePageClass(baseurl1)
    else : 
        if args.pl and args.pt : 
            idx = int(args.pi,10)
            res = getClass(args.pl)
            if len(res) == 0 :
                print("empty")
                exit()
            if args.pt == "voper" :
                qc.checkVOper(res[idx])
                f= open("class/vlist","a+")
                for s in qc.vlist :
                    tmps = json.dumps(s,ensure_ascii=False,indent="\t")+"\n"
                    f.write(json.dumps(s,ensure_ascii=False,indent="\t")+"\n")
                f.close()

                f = open("class/"+args.pi+"."+args.pl+".class","w+")
                f.write(res[idx].getdata())
                f.close()
            elif args.pt == "sqli":
                qc.checkSQLi(res[idx])
                f= open("class/vlist","a+")
                for s in qc.vlist :
                    tmps = json.dumps(s,ensure_ascii=False,indent="\t")+"\n"
                    f.write(json.dumps(s,ensure_ascii=False,indent="\t")+"\n")
                f.close()

                f = open("class/"+args.pi+"."+args.pl+".class","w+")
                f.write(res[idx].getdata())
                f.close()
            else : 
                print("please set options correctly (-pt)")    
        else : 
            page1 = savecwl(baseurl1)