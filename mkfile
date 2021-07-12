MKSHELL=rc


syntax-check-%:VQ: php-syntax-check-%


php-syntax-check-%:VQ:
	touch .php-syntax-stamp-tmp
	if ( ! test -e .php-syntax-stamp )
		touch -d '1970-01-01' .php-syntax-stamp
	stPN=`{realpath .php-syntax-stamp}
	@ { cd $stem && find -type f -name '*.php' -newer $stPN | xargs -i php -l '{}' } > /dev/null
	@ { cd $stem && find -type f -name '*.tpl' -newer $stPN | xargs -i php -l '{}' } > /dev/null
	mv .php-syntax-stamp-tmp .php-syntax-stamp
