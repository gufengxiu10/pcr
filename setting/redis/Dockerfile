FROM redis:latest

# COPY ./conf/redis.conf /usr/local/etc/redis/redis.conf

RUN cd /etc/apt \
    && echo "" > sources.list \
    && echo "deb http://mirrors.aliyun.com/debian/ buster main non-free contrib" >> sources.list \
    && echo "deb http://mirrors.aliyun.com/debian-security buster/updates main" >> sources.list \ 
    && echo "deb http://mirrors.aliyun.com/debian/ buster-updates main non-free contrib" >> sources.list \
    && echo "deb http://mirrors.aliyun.com/debian/ buster-backports main non-free contrib" >> sources.list \
    && echo "deb-src http://mirrors.aliyun.com/debian-security buster/updates main" >> sources.list \
    && echo "deb-src http://mirrors.aliyun.com/debian/ buster main non-free contrib" >> sources.list \
    && echo "deb-src http://mirrors.aliyun.com/debian/ buster-updates main non-free contrib" >> sources.list \
    && echo "deb-src http://mirrors.aliyun.com/debian/ buster-backports main non-free contrib" >> sources.list \
    && apt-get update 

