.DEFAULT_GOAL	:= deploy
TARGET	:= col2:~/public_html/mdcms.zxfo.com
OPTIONS	:= --style compact

reset:
	sass assets/scss/normalize.scss:assets/css/normalize.css --style compressed

deploy:
	rsync -av --delete --rsync-path=/home/tncxwjvw/bin/rsync --exclude-from ./exclude.txt -e ssh ./ $(TARGET)

style:
	sass assets/scss/style.scss:assets/css/style.css $(OPTIONS)

get:
	rsync -av --rsync-path=~/bin/rsync -e ssh $(TARGET)/ .
