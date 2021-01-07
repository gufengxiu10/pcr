FROM python

RUN cd /etc/apt \
    && echo "" > sources.list \
    && echo "deb https://mirrors.ustc.edu.cn/debian/ buster main contrib non-free" >> sources.list \
    && echo "deb https://mirrors.ustc.edu.cn/debian/ buster-updates main contrib non-free" >> sources.list \ 
    && echo "deb https://mirrors.ustc.edu.cn/debian/ buster-backports main contrib non-free" >> sources.list \
    && echo "deb https://mirrors.ustc.edu.cn/debian-security/ buster/updates main contrib non-free" >> sources.list \
    && echo "deb-src https://mirrors.ustc.edu.cn/debian/ buster main contrib non-free" >> sources.list \
    && echo "deb-src https://mirrors.ustc.edu.cn/debian/ buster-updates main contrib non-free" >> sources.list \
    && echo "deb-src https://mirrors.ustc.edu.cn/debian/ buster-backports main contrib non-free" >> sources.list \
    && echo "deb-src https://mirrors.ustc.edu.cn/debian-security/ buster/updates main contrib non-free" >> sources.list \
    && apt-get update \
    && mkdir ~/.pip && cd ~/.pip && touch pip.conf \
    && echo "[global]" >> pip.conf \
    && echo "index-url = https://mirrors.aliyun.com/pypi/simple/" >> pip.conf \
    && echo " " >> pip.conf \
    && echo "[install] = https://mirrors.aliyun.com/pypi/simple/" >> pip.conf \
    && echo "trusted-host=mirrors.aliyun.com" >> pip.conf \
    && pip install flask && pip install requests && pip install pyyaml \
    && pip install Pillow && pip install pixivpy && pip install python-dotenv \
    && apt-get install -y supervisor


ADD ./entrypoint.sh /usr/local/bin/

ENTRYPOINT [ "entrypoint.sh" ]

CMD [ "python3"]
