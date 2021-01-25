package main

import (
	"fmt"
	"log"

	"github.com/imroc/req"
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
	headr := req.Header{
		"App-OS":         "ios",
		"App-OS-Version": "12.2",
		"App-Version":    "7.6.2",
		"User-Agent":     "PixivIOSApp/7.6.2 (iOS 12.2; iPhone9,1)",
	}

	req.Debug = true
	req.SetProxyUrl("http://127.0.0.1:10809")

	param := req.Param{
		"mode":   "day",
		"filter": "for_ios",
	}

	r, err := req.Get("https://app-api.pixiv.net/v1/illust/ranking", headr, param)
	if err != nil {
		log.Fatal(err)
	}

	foo := make(map[string]interface{})

	r.ToJSON(&foo)       // 响应体转成对象
	log.Printf("%+v", r) // 打印详细信息
	// ul := "https://app-api.pixiv.net/v1/illust/ranking"
	// // hostName := "app-api.pixiv.net"

	// req, _ := http.NewRequest("Get", ul, nil)
	// purl, _ := url.Parse("http://127.0.0.1:8888")

	// // 代理
	// tr := &http.Transport{
	// 	Proxy: http.ProxyURL(purl),
	// }

	// client := http.Client{
	// 	Transport: tr,
	// }

	// q := req.URL.Query()
	// q.Add("mode", "day")
	// q.Add("filter", "for_ios")
	// req.URL.RawQuery = q.Encode()

	// req.Header.Set("App-OS", "ios")
	// req.Header.Set("App-OS-Version", "12.2")
	// req.Header.Set("App-Version", "7.6.2")
	// req.Header.Set("User-Agent", "PixivIOSApp/7.6.2 (iOS 12.2; iPhone9,1)")

	// resp, err := client.Do(req)

	// if err != nil {
	// 	fmt.Println(err)
	// } else {
	// 	body, _ := ioutil.ReadAll(resp.Body)
	// 	data := string(body)
	// 	fmt.Println(data)
	// 	content := make(map[string]interface{})
	// 	json.Unmarshal([]byte(data), &content)
	// }
	// defer resp.Body.Close()
	// nu := content["Answer"].([]interface{})[0].(map[string]interface{})["data"]

	// headers['App-OS'] = 'ios'
	// headers['App-OS-Version'] = '12.2'
	// headers['App-Version'] = '7.6.2'
	// headers['User-Agent'] = 'PixivIOSApp/7.6.2 (iOS 12.2; iPhone9,1)'
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
