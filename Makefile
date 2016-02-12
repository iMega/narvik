IMAGE = imega/narvik
CONTAINERS = imega_narvik imega_nginx
PORT = -p 80:80
ENV = PROD
SMTP_USER = user
SMTP_PASS = pass

build:
	@docker run --rm -v $(CURDIR):/data imega/composer:1.2.0 update
	@docker build -t $(IMAGE) .

start:
	@docker run -d --name imega_narvik \
		--env SMTP_USER=$(SMTP_USER) \
		--env SMTP_PASS="$(SMTP_PASS)" \
		-p 9000:9000 \
		imega/narvik

	@docker run -d --name imega_nginx \
		--link imega_narvik:service \
		-v $(CURDIR)/sites-enabled:/etc/nginx/sites-enabled \
		$(PORT) \
		leanlabs/nginx

stop:
	@-docker stop $(CONTAINERS)

clean: stop
	@-docker rm -fv $(CONTAINERS)

destroy: clean
	@docker rmi -f $(IMAGE)
