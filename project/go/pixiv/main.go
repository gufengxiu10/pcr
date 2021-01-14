package main

import (
	"crypto/md5"
	"encoding/hex"
	"fmt"
	"time"
)

func mmd5(text string) string {
	ctx := md5.New()
	ctx.Write([]byte(text))
	return hex.EncodeToString(ctx.Sum(nil))
}

func login() {
	hashsecret := "28c1fdd170a5204386cb1313c7077b34f83e4aaf4aa829ce78c231e05b0bae2c"
	nt := time.Now().UTC().Format("2006-01-02 15:04:05")
	cmd5 := mmd5(nt + hashsecret)
	fmt.Println(cmd5)
	fmt.Println(nt)
	// bashurl := "https://oauth.secure.pixiv.net"
	// param := req.Param{
	// 	"get_secure_url": 1,
	// 	"client_id":      "MOBrBDS8blbauoSck0ZfDbtuzpyT",
	// 	"client_secret":  "28c1fdd170a5204386cb1313c7077b34f83e4aaf4aa829ce78c231e05b0bae2c",
	// }

	// header := req.Header{
	// 	"User-Agent": "PixivAndroidApp/5.0.115 (Android 6.0; PixivBot)",
	// 	"X-Client-Time": nt,
	// 	"X-Client-Hash": cmd5,
	// }
	// req.Req
}

func main() {
	login()
}
