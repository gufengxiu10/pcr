FROM golang

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
    && apt-get update 

ENTRYPOINT ["/app.sh"]

CMD ["bash"]