from requests_html import HTMLSession
from fake_useragent import UserAgent
import json

ua = UserAgent()

loginUrl = "https://accounts.pixiv.net/login"
loginUrlApi = "https://accounts.pixiv.net/api/login?lang=zh"

session = HTMLSession()
proxie = {'http': "127.0.0.1:10809"}
res = session.get(loginUrl, headers={'user-agent': ua.chrome}, proxies=proxie)
print(json.loads(res.html.html))
