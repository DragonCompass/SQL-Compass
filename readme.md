# SQL Compass 최종 보고서

# **SQL Injection 점검 자동화 도구 개발**

## SQL_COMPASS

![img/Untitled.png](img/Untitled.png)

![img/Untitled%201.png](img/Untitled%201.png)

---

## 목차

[**1. 프로젝트 개요**]()

​	1.1 프로젝트 주제

​	1.2 프로젝트 배경

​	1.3 프로젝트 목표

**[2. 프로젝트 조직도]()**

​	2.1 프로젝트 조직도

​	2.2 프로젝트 책임 및 역할

**[3. 프로젝트 수행일정]()**

**[4. 개발 환경 구축]()**

​	4.1 테스트 환경 구축

​	4.2 협업 환경 구축

**[5. 모듈 개발]()**

​	5.1 모듈 개발 구조도

​	5.2 모듈 로직 설명

​	5.2 웹 크롤링 모듈 개발

​		5.2.1 모듈 상세 명세

​	5.3 SQL Query 판별 모듈 개발

​	5.3.1 모듈 상세 명세

​		5.4 분석 결과 웹 전송 모듈

​	5.4.1 모듈 상세 명세

**[6. 시각화 대시보드 서버 구축]()**

​	6.1 부트스트랩의 sbadmin 테마 구축

​	6.2 레이아웃 및 기능 지정

​	6.3 검사결과 통계자료 이용하여 시각화

​		6.3.1 공격 유효 벡터 폼의 이름 목록 출력

​		6.3.2 공격 유효 쿼리 스트링 목록 출력

​		6.3.3 취약한 페이지 비율을 시각화 자료 표현

​		6.3.4 페이지별 취약한 쿼리 개수 시각화 자료 표현

**[7. 모듈과 서버 연결 모듈 구축]()**

​	7.1 JSON 형식으로 데이터 주고받기

**[8. 최종 모듈 테스트 및 결과]()**

---

## 1. **프로젝트 개요**

### **1-1. 프로젝트 주제 및 기간**

- 프로젝트 주제 : SQL Injection 자동화 툴 개발
- 프로젝트 기간 : 2020.9.21. ~ 2020.11.21.
- 프로젝트 팀명 : SQL COMPASS

### **1-2. 프로젝트 배경**

 **1) SQL Injection 이란?**

SQL Injection은 코드 인젝션의 한 기법으로 클라이언트의 입력값을 조작하여 서버의 데이터베이스를 공격할 수 있는 공격방식을 말한다. 또한 가장 일반적인 웹 해킹 기술에 속하며, 웹 페이지의

입력을 통해서 SQL문에 악의적인 코드를 삽입하는 방식으로 파라미터 조작이라고도 할 수 있다.

주로 사용자가 입력한 데이터를 제대로 필터링, 이스케이핑 하지 못했을 때 발생하며 공격의 쉬운 난이도에 비해 파괴력이 어마어마하기 때문에, 시큐어 코딩을 할 때 고려하는 것이 필수적이다.

**[그림 1-1]** 에서 보는바와 같이 SQL Injection은 최근 몇 년간 국내외 웹 사이트들의 무자비하고 방대한 해킹 공격의 도구로 이용되었으며, 그 피해 또한 막대한 상황이다.

![img/Untitled%202.png](img/Untitled%202.png)

[그림 1-1] SQL Injection 관련 기사들

**2) SQL Injection 취약점 개요**

SQL Injection 취약점을 간략하게 정의하면 웹 페이지의 입력 또는 매개변수인 파라미터 값에 SQL Query를 주입하여 데이터베이스의 데이터 조작이 가능한 취약점이라고 할 수 있다.

즉, SQL Injection은 Query를 이용하여 데이터베이스를 비정상적으로 조작하는 공격 기술이라고 할 수 있다. 이 취약점은 데이터베이스와 웹 어플리케이션이 연동되면서 태생 되었으며 허용된 권한, 기능, 실행 가능한 SQL Query에 따라 피해정도가 다르다.

![img/Untitled%203.png](img/Untitled%203.png)

[그림 1-2] SQL Injection 도식화

**3) OWASP TOP10위에 매년 등재**

이 취약점은 웹 해킹에서 위험도가 매우 높은 취약점으로 OWASP의 웹 어플리케이션 10대 보안취약점에서도 지금까지 한번도 누락된 적이 없다.

OWASP(Open Web Application Security Project) 란? 시기별 개발자 및 웹 응용프로그램 보안을 위한 표준 인식 문서로 웹 어플리케이션에 대한 가장 중요한 보안 위험에 대한 순위를 나타낸다.

매 3~4년 주기로 발표되어지고 있고 **[그림 1-2]**에서 보는바와 같이 거의 매 발표마다 동일한 항목을 보여주고 있다. SQL Injection은 OWASP에 등재된 보안 위험 1위에 해당하는 Injection공격의 한 종류이며 최근 몇 년간 웹 어플리케이션 보안 분야에서 가장 위험한 보안 위험이라고 판단하고 있다.

![img/Untitled%204.png](img/Untitled%204.png)

[그림 1-3] OWASP 2013, 2017

**4) SQL Injection 관련 점검 도구 부재**

이러한 강력한 위험에도 불구하고 실무에서 사용하는 SQL Injection 점검 도구는 유료로 사용할 수 있는 Acunetix 이외엔 거의 전무한 상황이고, 현재 SQL Injection 자동화 도구로 잘 알려진 SQLMAP은 취약점을 점검해주는 도구라기보다는 이미 발견한 취약 항목들을 대상으로 SQL Injection을 이용하여 웹 사이트를 공격하여 데이터베이스를 추출하는 방식으로 방향성이 맞춰진 공격 도구이다.

**[그림 1-3]** 에서 보는바와 같이 우리가 개발한 SQL Injection 점검 도구인 SQL COMPASS는 SQLMAP처럼 SQL Injection 공격에 초점을 맞추기 보다, SQL Injection 공격에 대한 웹 사이트 내 파라미터별 취약한 수준을 점검해주고, 취약한 쿼리를 출력해 주며, 취약한 쿼리에 따라 해당 파라미터를 고위험군 / 저위험군 수준으로 분류해 주는 기능을 제공한다.

![img/Untitled%205.png](img/Untitled%205.png)

[그림 1-4] SQLMAP, SQLCOMPASS 공통점, 차이점

### **1-3. 프로젝트 목표**

**1) 목표**

시중에 잘 알려진 SQLMAP과는 다른 기능과 방향성을 제공하고, Acunetix가 구현하고 있는 방향성을 어느정도 수용하는 방식으로 무료 Open-Source SQL Injection 점검 도구를 제공하는 것이 목표이다.

또한 사용자 친화적인 도구를 제작하기 위해 Web상의 시각화 대시보드 템플릿을 이용하여, 사용자로 하여금 타 도구에 비해 더 편리한 도구라고 생각될 만큼의 GUI적인 부분을 제공하는 것이 프로젝트의 일차척인 목표이며, 해당 웹 사이트의 SQL Injection 취약점에 관련된 점검 내용을 Report 형식으로 사용자에게 제공하는 것이 궁극적 목표이다.

**2) 세부 목표**

![img/Untitled%206.png](img/Untitled%206.png)

[그림 1-5] 프로젝트 세부목표

- 웹 크롤링

    Python의 웹 크롤링 관련 모듈인 BeautifulSoup, Request, Json, sys 모둘 등을 이용하여 지정 웹 페이지상의 Attack Vector 관련 파라미터를 자동으로 수집하는 모듈을 개발하였다. 일반적인 크롤링 목적이 아닌, SQL Injection관련 취약점을 판별하고 점검해 주는 목적으로 웹 크롤링 모듈을 고도화 하였다.

- SQL 취약점 분류 도구 (쿼리 출력 값 비교를 통한 취약점 점검)

    웹 크롤링을 통해 출력된 쿼리 값을 비교하여 해당 웹 사이트의 SQL Injection 공격에 대한 취약한 쿼리를 출력해주고, 해당 페이지와 연동된 페이지 중 취약점이 발견된 페이지를 출력해 주고, 고위험군 / 저위험군 으로 판별 및 분류하여 관련 페이지 개수를 출력해주는 기능을 구현한다.

- Web GUI (사용자 친화적인 환경 구축)

    사용자 친화적인 웹 브라우저 환경에서 점검 결과를 제공하기 위해 웹 서버를 구축하였다. 취약점에 관련된 각종 정보들을 막대 그래프, 원형 그래프, 표, 사각 텍스트 박스 등의 각종 시각화 통계 자료들로 출력하여 사용자로 하여금 이용하기 편리하다고 생각될 만큼의 기능을 제공한다.

    해당 웹 서버는 Docker상에 debian 컨테이너로 구성되었으므로, 도커 파일과 구성요소들의 소스 코드만 있다면 어디서나 간편하게 환경을 구축하여 사용할 수 있게 제공할 수 있다.

- 취약점 해결 방안 제시 (사이트 취약점 점검 결과 리포팅)

    SQL Injection 취약점에 관련된 점검 내용을 사용자 친화적인 웹 사이트상의 시각화 통계 자료로 표현하는 것뿐만 아니라, Report 형식으로 엑셀 파일 형태로 사용자에게 점검 내용을 제공할 예정이다. 

    단순히 시각화 통계자료를 이용하여 한 눈에 취약점과 취약한 정도를 판단하는 기능뿐만 아니라 세부적으로 해당 페이지가 어떠한 쿼리에 취약한지, 관련 URL 링크 중에 어떤 링크가 위험군에 해당하는 URL인지 정확하게 세부적으로 판단할 수 있는 기능을 제공할 예정이다.

---

## **2. 프로젝트 조직도**

### **2-1. 프로젝트 조직도**

![img/Untitled%207.png](img/Untitled%207.png)

[그림 2-1] 프로젝트 조직도

### **2-2. 프로젝트 책임 및 역할**

![img/Untitled%208.png](img/Untitled%208.png)

[그림 2-2] 프로젝트 책임 및 역할

---

## **3. 프로젝트 수행일정**

### **3-1. 프로젝트 WBS**

프로젝트 기간인 2020.9.21. ~ 2020.11.21. 동안 해야 할 일을 잘 분배하여 WBS를 작성함.

![img/Untitled%209.png](img/Untitled%209.png)

[그림 3-1] 프로젝트 WBS

---

## **4. 개발환경 구축**

### **4-1. 테스트 환경 구축**

**1) SQL Injection 취약점 점검용 테스트 사이트 구축**

- [http://pingu6615.phps.kr/ksj/](http://pingu6615.phps.kr/ksj/)
- http://mentoring.ton80.net/)

SQL Injection 공격은 상용 페이지에 실행할 수 없는 엄연한 사이버 공격이므로, 프로젝트 기 간동안 SQL Injection 공격을 마음대로 원하는 만큼 시연 해 볼 수 있는 테스트 환경의 웹 사이트를 구축하여 프로젝트 진행의 효율성을 향상시켰고, 더 깊게 분석하여 도구의 성능을 향상시킬 수 있는 배경을 마련하였다.

![img/Untitled%2010.png](img/Untitled%2010.png)

[그림 4-1] SQLi 공격 테스트 사이트

### **4-2. 협업 환경 구축**

**1) Github를 이용한 협업 환경 구축**

- https://github.com/DragonCompass

개발 과정에서 서로의 코드를 공유하고 보완하기 위해 협업환경으로 GitHub를 이용하였다. 또한 다른 조들에 비해 인원이 부족한 상황을 고려하여 좀 더 효율적인 개발 환경을 구축하기 위해 GitHub가 제공해주는 협업 기능을 최대한 활용하였다.

**2) Google meet을 이용한 온라인 회의 환경**

- https://meet.google.com/fnw-qmfr-boa

개발 기간동안 코로나19로 인해 직접 대면하여 프로젝트를 진행하기에 어려움이 있어 온라인 화상 회의 환경인 Google meet를 이용하였다.

**3) 프로젝트 자료 공유 드라이브**

- [http://asq.kr/frrsped5oEXl](http://asq.kr/frrsped5oEXl)

구글드라이브를 통해 프로젝트 관련자료를 공유하여 협업의 효율성을 향상시켰다.

![img/Untitled%2011.png](img/Untitled%2011.png)

[그림 4-2] 협업 도구 (github, Google meet, Google drive)

---

## **5. 모듈개발**

### **5-1. 프로그램 도식도 및 모듈개발 구조도**

- 개발 언어 : Python
- 활용 모듈 : BeautifulSoup, Requests, Json sys etc

**1) getInfo함수**

페이지 정보를 담고있는 PageSet클래스를 반반환한다. 가장먼저, 사용자에게 입력받은 BaseURL(크롤링의 시작지점이 되는 URL)을 인자로 getInfo 함수를 호출한다. getInfo 함수에서는 최종적으로 PageSet 클래스로 구성된 리스트를 반환하게된다.

**2) PageSet클래스**

클래스는 URL을 인자로 받아 해당 페이지에서 크롤링되어 수집되는 페이지의 URL과 폼, 링크 정보 리스트(폼 별 path, method, Form list, href list) 로 초기화 된다.

또한 Page정보를 따로 저장하는 getdata 함수와 page정보를 출력하는 showdata와 같은 함수들이 존재한다.

**3) getform 함수**

PageSet클래스에서 페이지 폼정보를 담고있는 flist가 초기화 될 때 호출 되는 함수로, 페이지 내에 존재하는 폼 정보들을 수집하여 formset클래스 로 구성한다. 만약 form태그에 action, method 정보가 없는 경우 onclick, Ajax로 동작하는 폼으로 간주하여 parsescript함수 를 호출하여 스크립트를 추적하여 action값과 method값을 수집한다. 최종적으로 formset클래스 리스트로 구성된 리스트를 반환한다.

**4) getlink 함수**

PageSet클래스에서 페이지 링크정보를 담고있는 hreflist가 초기화 될 때 호출되는 함수로, 페이지 내에 존재하는 링크 정보들을 수집하여 hrefset클래스 로 구성한다. 최종적으로 hrefset 클래스로 구성된 리스트를 반환한다.

**5) checkVOper함수**

해당페이지의 hreflist의 각 요소인 href를 인자로 , 파라미터 타입이 digit인 요소를 대상으로, 기존의 파라미터 값을 전송한 결과와 연산자가 내포된 파라미터 값을 전송한 결과를 checkResSame함수로 같은 결과값을 비교하여 낮은 위험도의 인젝션 취약점이 발생하는 href요소를 판별한다.

**6) checkSQLi**

해당 페이지의 hreflist의 각 요소인 href를 인자로, checknormal 함수를 통해 정상적인 결과를 반환하는지 판별한 후 cheatsheet에 있는 sql injection query를 전송하여 sql injection취약점이 발생하는 href요소를 판별한다.

![img/Untitled%2012.png](img/Untitled%2012.png)

[그림 5-1] 프로그램 도식도

![img/Untitled%2013.png](img/Untitled%2013.png)

[그림 5-2] 모듈개발 구조도

## **5-2. 웹 크롤링 모듈**

**5-2-1. 모듈 상세 명세**

**1) 클래스 선언**

- pageset 클래스

![img/Untitled%2014.png](img/Untitled%2014.png)

[표 5-1] pageset 클래스

- formset 클래스

![img/Untitled%2015.png](img/Untitled%2015.png)

[표 5-2] formset 클래스

- hrefset 클래스

![img/Untitled%2016.png](img/Untitled%2016.png)

[표 5-3] href 클래스

- argset 클래스

![img/Untitled%2017.png](img/Untitled%2017.png)

[표 5-4] formset 클래스

**2) 함수 선언**

- show_diff 함수

![img/Untitled%2018.png](img/Untitled%2018.png)

[표 5-5] show-diff 함수

- parsescript 함수

![img/Untitled%2019.png](img/Untitled%2019.png)

[표 5-6] parsescript 함수

- getsoup 함수

![img/Untitled%2020.png](img/Untitled%2020.png)

[표 5-7] getsoup 함수

- getform 함수

![img/Untitled%2021.png](img/Untitled%2021.png)

[표 5-8] getform 함수

- getlink 함수

![img/Untitled%2022.png](img/Untitled%2022.png)

[표 5-9] getlink 함수

- getprotocol 함수

![img/Untitled%2023.png](img/Untitled%2023.png)

[표 5-10] getprotocol 함수

## **5-3. SQL Injection 판별 모듈**

**5-3-1 모듈 상세 명세**

**1) 함수 선언**

- makeResult() 함수

![img/Untitled%2024.png](img/Untitled%2024.png)

[표 5-11] makeResult 함수

- getJSON() 함수

![img/Untitled%2025.png](img/Untitled%2025.png)

[표 5-12] getJSON 함수

- checkSQLi2() 함수

![img/Untitled%2026.png](img/Untitled%2026.png)

[표 5-13] checkSQLi2 함수

- checkVOper() 함수

![img/Untitled%2027.png](img/Untitled%2027.png)

[표 5-14] checkVOper 함수

- checkNormal() 함수

![img/Untitled%2028.png](img/Untitled%2028.png)

[표 5-15] checkNormal 함수

- makeAnormal() 함수

![img/Untitled%2029.png](img/Untitled%2029.png)

[표 5-16] makeAnormal 함수

- checkResSame() 함수

![img/Untitled%2030.png](img/Untitled%2030.png)

[표 5-17] checkResSame 함수

- checkError() 함수

![img/Untitled%2031.png](img/Untitled%2031.png)

[표 5-18] checkError 함수

**5-3-2. SQL 공격 쿼리 수집 및 분류**

잘 알려진 SQL injection 공격용 Query를 최대한 많이 수집하여 취약점 검증을 위한 cheat-sheet 구성

![img/Untitled%2032.png](img/Untitled%2032.png)

[그림5-6] SQLi Query cheat sheet

수집된 쿼리중, 자동화하여 적용하기에 적절한 폼과 쿼리 분류

![img/Untitled%2033.png](img/Untitled%2033.png)

[그림5-7] 분류된 SQLi Query

**5-3-3. 분류된 Query를 이용하여 웹 사이트 취약점 판별 및 검증**

- 분류된 Query를 이용하여 취약점이 발견된 페이지의 숫자와 총 취약점 발생 파라미터의 개수를 파악한다.
- 웹 페이지에서 결과값 활용이 용이하게 JSON 모듈을 사용하여 결과값 반환
- 실행된 쿼리 종류에 따라 웹 페이지를 고위험군 / 저위험군 으로 분류

**5-4. 분석 결과 웹 전송 모듈**

각 모듈에서 필요한 기능들을 정리하여 실행하고, 실행된 결과들을 한데 모아서 처리하는 역할을 하는 모듈로 기능적인 부분에서 AJAX와의 연동을 고려하여 개발하였다.

**5-4-1. 모듈 상세 명세**

**1) 함수 선언**

- makePageClass 함수

![img/Untitled%2034.png](img/Untitled%2034.png)

[표 5-19] makePageClass 함수

- makeHrefClass 함수

![img/Untitled%2035.png](img/Untitled%2035.png)

[표 5-20] makeHrefClass 함수

- getClass 함수

![img/Untitled%2036.png](img/Untitled%2036.png)

[표 5-21] getClass 함수

- savecwl 함수

![img/Untitled%2037.png](img/Untitled%2037.png)

[표 5-22] savecwl 함수

- main 함수

![img/Untitled%2038.png](img/Untitled%2038.png)

[표 5-23] main 함수

---

## **6. 시각화 대시보드 서버 구축**

### **6-1. 부트스트랩의 SBadmin 테마 구축**

![img/Untitled%2039.png](img/Untitled%2039.png)

[그림6-1] 부트스트랩의 SBadmin 테마

**1) SBadmin 테마 특징**

![img/Untitled%2040.png](img/Untitled%2040.png)

[그림6-2] SBadmin 테마 특징

**6-2. 레이아웃 및 기능 지정**

- 검색창에 URL 입력시 SQLi 취약점 검사 후 통계 페이지로 이동

![img/Untitled%2041.png](img/Untitled%2041.png)

[그림6-3] 대시보드 서버 레이아웃 기능

점검 대상 URL 선택 시, 보다 더 사용자가 접근하기 친근하게 만들기 위해 AJAX를 이용한 비동기 동적 페이지 구축을 진행하였다 .

파이썬 코드가 페이지의 각 부분별로 나누어 검사를 진행하고, 그 결과를 웹 페이지로 전송하면 웹 페이지에서는 해당 결과를 받아와 사용자에게 실시간 검사 현황을 출력하는 식으로 동작한다.

**6-3. 검사결과 통계자료 이용하여 시각화**

![img/Untitled%2042.png](img/Untitled%2042.png)

[그림6-4] 웹 페이지 관련 검사자료 통계

- 검사한 페이지 수, 취약점 발견된 페이지 수, 취약점 개수, 취약점 페이지 비율, 고위험 / 저위험 페이지 등 페이지 관련 검사자료 이외에도 여러 가지 검사 결과에 대해 통계자료를 시각화하여 대시보드에 출력, 웹 페이지 구성도를 높임.

**6-3-1. 공격 유효 벡터 폼의 이름 목록 출력**

- 공격 유효 벡터 폼의 이름과 목록을 출력해 준다. (출력된 폼과 쿼리 검색 기능 추가)

![img/Untitled%2043.png](img/Untitled%2043.png)

[그림6-5] 공격 유효 URL, 벡터 폼과 쿼리 정보

**6-3-2. 공격 유효 쿼리 스트링 목록 출력**

- 공격 유효 쿼리 스트링 목록 출력

![img/Untitled%2044.png](img/Untitled%2044.png)

[그림6-6] 공격 유효 쿼리 스트링 목록

**6-3-3. 취약한 페이지 비율을 시각화 자료 표현**

- 고위험 / 저위험 / 정상 / 에러 항목으로 나누어 원형(도넛형) 그래프로 표현

![img/Untitled%2045.png](img/Untitled%2045.png)

[그림6-7] 취약한 페이지 비율 원형 그래프

**6-3-4. 페이지별 취약한 쿼리 개수 시각화 자료 표현**

- 대상 페이지에서 연결되는 페이지들에 대해 취약점 발견 통계를 막대 그래프로 출력

![img/Untitled%2046.png](img/Untitled%2046.png)

[그림6-8] 연결 페이지 취약점 발견 통계 그래프

---

### **7. 모듈과 서버 연결 모듈 구축**

**7.1 JSON 형식으로 데이터 주고받기**

- python에서 php로 데이터를 전송할 때 JSON 형식으로 변환
- php에서 python으로 명령 실행할 때 exec명령어 이용

![img/Untitled%2047.png](img/Untitled%2047.png)

[그림7-1] python과 php 데이터 형식 주고받기

---

### **8. 최종 모듈 테스트 및 결과**

최초 기획 당시부터 다양한 기능의 확장성을 고려한 개발을 진행 하였으며, 다른 기능의 추가가 용이하도록 설계하였으며 추후 Apache, PHP, Mysql이 아닌 그 외의 시스템에서도 확장하는 것이 목표이다. 또한 오픈소스 프로젝트의 장점을 살려서 미흡한 점들에 대해서는 오픈소스 커뮤니티의 도움을 받아 도구의 완성도를 높이는 것을 기대하고 있다.

![img/Untitled%2048.png](img/Untitled%2048.png)

[그림8-1] 웹 결과화면 출력

![img/Untitled%2049.png](img/Untitled%2049.png)

[그림8-2] 취약점 점검 결과

---

### **9. 참고문헌**

1) SQL INJECTION 공격 자동화 기법, 유현수, 2019

2) Do it! 점프 투 파이썬 –전면 개정판, 박응용, 2019

3) Linux Basics for Hackers ,Getting Started with Networking, Scripting, and Security in Kali – OccupyTheWeb

4) A Bug Hunter's Diary: A Guided Tour Through the Wilds of Software Security - TobiasKlein

5) OWASP Top 10 – 2017 – OWASP Foundation

6) Design of An Automated Penetration Testing Tool for SQL/NoSQL Injection Vulnerabilities – 오정욱,도경구