import sys
import argparse


parser = argparse.ArgumentParser(description= "mode -m ")
parser.add_argument('-m', help="Mode", required=False)
args = parser.parse_args()

if args.m == "go" :
    f = open("tmp","r") 
    res = int(f.readline(),10)+1
    f.close()
    for i in range(res,res+5):
        print(i)
    f = open("tmp","w") 
    f.write(str(i))
else :
    f = open("tmp","w") 
    for i in range(0,5):
        print(i)
    f.write(str(i))

# for i in range(0,100):
    # print(i)