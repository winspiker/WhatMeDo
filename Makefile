get_container_name = $(lastword $(subst -, ,$1))
dc_bin := $(shell (command -v docker-compose) 2> /dev/null)
#app_name = winspiker/whatmedo

up:
	@$(dc_bin) up -d

enter-%:
	@$(dc_bin) run $(call get_container_name, $@) bash

enter: enter-alpine

rm-%:
	@$(dc_bin) rm -fs $3

rm: rm-apline

build:
	@$(dc_bin) build

#clear: rm-alpine
#	@$(docker images | grep $(app_name) | awk '{ print $3 }' | docker rmi) 2> /dev/null
