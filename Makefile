APP_NAME = jomlahbazar
IMAGE_NAME ?= 619432038635.dkr.ecr.us-east-2.amazonaws.com/$(APP_NAME)
IMAGE_VERSION ?= $(shell docker/tag_helper.sh)
BRANCH_NAME ?= $(shell git rev-parse --abbrev-ref HEAD)
SHORT_COMMIT_HASH ?= $(shell git rev-parse --short HEAD)
SHELL = /bin/bash

GIT_REF = $(shell git rev-parse HEAD)

git-archive:
	git archive --format tar.gz --output docker/archive.tar.gz $(GIT_REF)

docker-build:
	docker build -t $(IMAGE_NAME):$(BRANCH_NAME)-$(SHORT_COMMIT_HASH) -f docker/Dockerfile .

docker-push:
	docker push $(IMAGE_NAME):$(BRANCH_NAME)-$(SHORT_COMMIT_HASH)

docker: docker-build docker-push

deploy:
	aws ecr get-login --no-include-email --region eu-west-1
	docker tag $(IMAGE_NAME):$(BRANCH_NAME)-$(SHORT_COMMIT_HASH) $(IMAGE_NAME):latest
	docker push $(IMAGE_NAME):$(BRANCH_NAME)-$(SHORT_COMMIT_HASH)
	docker push $(IMAGE_NAME):latest

release: docker deploy

laravel-env-changes:
	sed -i 's|{{ DB_HOST }}|$(DB_HOST)|' docker/config/laravel.env
	sed -i 's|{{ DB_NAME }}|$(DB_NAME)|' docker/config/laravel.env
	sed -i 's|{{ DB_USERNAME }}|$(DB_USERNAME)|' docker/config/laravel.env
	sed -i 's|{{ DB_PASSWORD }}|$(DB_PASSWORD)|' docker/config/laravel.env

default: docker