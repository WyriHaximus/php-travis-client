all:
	composer run-script qa-all --timeout=0

all-coverage:
	composer run-script qa-all-coverage --timeout=0

ci:
	composer run-script qa-ci --timeout=0

ci-with-coverage:
	composer run-script qa-ci-coverage --timeout=0

contrib:
	composer run-script qa-contrib --timeout=0

init:
	composer ensure-installed

cs:
	composer cs

unit:
	composer run-script unit --timeout=0

unit-coverage:
	composer run-script unit-coverage --timeout=0

ci-coverage: init
	composer ci-coverage

generate-resources: init
	./vendor/bin/api-client-resource-generator ./resources.yml
