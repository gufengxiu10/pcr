from pixivpy3 import ByPassSniApi
from datetime import datetime, timedelta
import os
import json
import requests
from operator import itemgetter

username = "annghanyuu@gmail.com"
password = "Freedomx102"


def main():
    # timeout = 3
    # hostname = "app-api.pixiv.net"
    # url = "https://1.0.0.1/dns-query"  # 先使用1.0.0.1的地址
    # params = {
    #     'ct': 'application/dns-json',
    #     'name': hostname,
    #     'type': 'A',
    #     'do': 'false',
    #     'cd': 'false',
    # }
    # response = requests.get(url, params=params, timeout=timeout)

    # ki = sorted(response.json()['Answer'], key=itemgetter('TTL'), reverse=True)
    # print(ki)
    # print(response.json()['Answer'])
    # return
    api = ByPassSniApi()  # Same as AppPixivAPI, but bypass the GFW
    api.require_appapi_hosts(hostname="public-api.secure.pixiv.net")
    # api.set_additional_headers({'Accept-Language':'en-US'})
    api.set_accept_language('en-us')

    api.login(username, password)
    json_result = api.illust_ranking(
        'day',
        date=(datetime.now() - timedelta(days=6)).strftime('%Y-%m-%d'),
        offset=150)

    directory = "illusts2"
    if not os.path.exists(directory):
        os.makedirs(directory)

    # download top3 day rankings to 'illusts' dir
    for idx, illust in enumerate(json_result.illusts):
        image_url = illust.meta_single_page.get('original_image_url',
                                                illust.image_urls.large)
        print("%s: %s" % (illust.title, image_url))

        # try four args in MR#102
        if idx == 0:
            api.download(image_url, path=directory, name=None)
        elif idx == 1:
            url_basename = os.path.basename(image_url)
            extension = os.path.splitext(url_basename)[1]
            name = "illust_id_%d_%s%s" % (illust.id, illust.title, extension)
            api.download(image_url, path=directory, name=name)
        elif idx == 2:
            api.download(image_url,
                         path=directory,
                         fname='illust_%s.jpg' % (illust.id))
        else:
            # path will not work due to fname is a handler
            api.download(image_url,
                         path='/foo/bar',
                         fname=open(
                             '%s/illust_%s.jpg' % (directory, illust.id),
                             'wb'))


if __name__ == '__main__':
    main()

# import asyncio

# import sys
# # import time
# import random

# from pixivpy_async import PixivClient
# from pixivpy_async import AppPixivAPI
# from pixivpy_async import PixivAPI

# async def illust_detail(aapi, i):
#     print('%s,' % i, end="")
#     sys.stdout.flush()
#     await aapi.illust_detail(random.randint(19180000, 19189999))

# async def _test_async_illust_detail(num):
#     async with PixivClient() as client:
#         aapi = AppPixivAPI(client=client)
#         papi = PixivAPI(client=client)
#         print(1)
#         await aapi.login(username, password)
#         print(2)
#         await papi.login(username, password)
#         tasks = [
#             asyncio.ensure_future(illust_detail(aapi, i)) for i in range(num)
#         ]
#         await asyncio.wait(tasks)

# loop = asyncio.get_event_loop()
# task = [asyncio.ensure_future(_test_async_illust_detail(10))]
# loop.run_until_complete(asyncio.wait(task))
# loop.close

# # client = PixivClient()
# # aapi = AppPixivAPI(client=client.start())
# # # Doing stuff...
# # await client.close()
# async def test2(i):
#     r = await other_test(i)
#     print(2, r)

# async def other_test(i):
#     print(1)
#     await asyncio.sleep(4)
#     print(time.time() - start)
#     return i

# url = [
#     r"https://baidu.com1",
#     r"https://baidu.com2",
#     r"https://baidu.com3",
# ]
# start = time.time()
# loop = asyncio.get_event_loop()
# task = [asyncio.ensure_future(test2(i)) for i in url]
# loop.run_until_complete(asyncio.wait(task))
# endtime = time.time() - start
# print(endtime)
# loop.close
