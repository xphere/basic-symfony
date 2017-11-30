FROM nginx:alpine

COPY files/main /

ENV LOG_DIR /var/logs/nginx

RUN mkdir -p $LOG_DIR \
 && touch $LOG_DIR/access.log \
          $LOG_DIR/error.log \
 && ln -sf /dev/stdout $LOG_DIR/access.log \
 && ln -sf /dev/stdout $LOG_DIR/error.log \
;

EXPOSE 80
