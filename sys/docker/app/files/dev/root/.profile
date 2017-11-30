# Run host profile if exists
[ -r /srv/.profile ] && . /srv/.profile

PATH=.:$PATH

alias xon="xdebug on"
alias xoff="xdebug off"

umask 022
