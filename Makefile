get_container_name = $(lastword $(subst -, ,$1))
dc_bin := $(shell (command -v docker-compose) 2> /dev/null)
base_image_tag = whatmedo-base:latest
#app_name = winspiker/whatmedo

up:
	@$(dc_bin) up -d

enter-%:
	@$(dc_bin) exec $(call get_container_name, $@) bash

enter: enter-app

rm-%:
	@$(dc_bin) rm -fs $(call get_container_name, $@)

rm: rm-app

build-base: clear
	@docker build -t $(base_image_tag) docker/php/

build-app:
	@$(dc_bin) build

clear:
	@$(docker images | grep none | awk '{ print $3 }' | docker rmi -f) 2> /dev/null

