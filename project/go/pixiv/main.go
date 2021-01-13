package main

import (
	"crypto/tls"
	"fmt"
	"io/ioutil"
	"net/http"
)

//TtlStruct 定义类型
type ttlStruct struct {
	ttL  string
	data string
	name string
}

func (a *ttlStruct) add() {
	fmt.Println(a.ttL, a.data)
}

type inst []interface{}

func main() {
	url := "https://1.0.0.1/dns-query"
	hostName := "app-api.pixiv.net"
	client := &http.Client{}
	req, _ := http.NewRequest("Get", url, nil)
	// 代理
	tr := &http.Transport{TLSClientConfig: &tls.Config{
		InsecureSkipVerify: true,
	}}

	proxyUrl := url.Parse("http://127.0.0.1:8888")
	tr.Proxy = http.ProxyURL(proxyUrl)

	q := req.URL.Query()
	q.Add("ct", "application/dns-json")
	q.Add("name", hostName)
	q.Add("type", "A")
	q.Add("do", "false")
	q.Add("cd", "false")
	req.URL.RawQuery = q.Encode()
	resp, err := client.Do(req)
	if err != nil {
		fmt.Println(10)
	}

	defer resp.Body.Close()

	body, _ := ioutil.ReadAll(resp.Body)

	fmt.Println(string(body))

	// ki := ttlStruct{"1", "3", "5"}
	// ki.add()
	// rs, _ := http.Get("https://1.0.0.1/dns-query?ct=application/dns-json&name=baidu.com&type=A&do=false&cd=false")
	// content, _ := ioutil.ReadAll(rs.Body)
	// ncontent := string(content)
	// mapContent := make(map[string]interface{})
	// json.Unmarshal([]byte(ncontent), &mapContent)
	// for _, ttl := range mapContent["Authority"].([]interface{}) {
	// 	sort.Sort(sort.Reverse(ttl))
	// }

	// sort.Sort()
	// fmt.Println(mapContent["Authority"])
}
