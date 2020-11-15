FROM debian:buster


RUN export DEBIAN_FRONTEND=noninteractive && \
	apt-get update && apt-get -y upgrade && \
	apt-get install -y git composer php-fdomdocument

RUN useradd -ms /bin/bash checker

COPY ./ /usr/src/roundcube-behat-checker
RUN chown -R checker /usr/src/roundcube-behat-checker
WORKDIR /usr/src/roundcube-behat-checker

USER checker
RUN composer install

CMD "/usr/src/roundcube-behat-checker/check_behat.sh"
