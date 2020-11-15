#!/usr/bin/make

NAME=roundcube-behat-checker
VERSION=latest

include variables.sh

build:
	docker build -t $(NAME):$(VERSION) .

run:

	docker run --rm $(NAME):$(VERSION)

clean:
	docker rmi $(NAME):$(VERSION)
