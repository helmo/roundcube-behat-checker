FROM debian:buster

RUN export DEBIAN_FRONTEND=noninteractive && \
	apt-get update && apt-get -y upgrade && \
	apt-get install -y git composer php-fdomdocument && \
	cd /usr/src && \
	git clone https://github.com/helmo/roundcube-behat-checker.git && cd roundcube-behat-checker && \
	composer install

COPY content /

CMD "/run.sh"
