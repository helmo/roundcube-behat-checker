#!/usr/bin/make

NAME=roundcube-behat-checker
VERSION=latest

include .env
export $(shell sed 's/=.*//' .env)

build:
	docker build -t $(NAME):$(VERSION) .

run:

	docker run --rm -e USERNAME=$(USERNAME) -e PASSWORD=$(PASSWORD) -e URL=$(URL) $(NAME):$(VERSION)

clean:
	docker rmi $(NAME):$(VERSION)
