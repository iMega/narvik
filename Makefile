IMAGE = imega/narvik
ENV = PROD

build:
	@docker run --rm -v $(CURDIR):/data imega/composer:1.2.0 update
	@docker build -t $(IMAGE) .

start:
	@docker run -d --name=imega_narvik imega/narvik
	@docker run -d --link="imega_narvik:service" leanlabs/nginx

destroy: clean
	@docker rmi -f $(IMAGE)
