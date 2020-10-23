#!make

test-new-from-local-file:
	@cd tests-output && rm -fr demo && php ../bin/producer new --file ../templates/8.0/jetstream-inertia.yml demo

test-new-from-template:
	@cd tests-output && rm -fr demo && php ../bin/producer new --template laravel:8.0:jetstream-inertia demo

test-apply:
	@cd tests-output && rm -fr demo && php ../bin/producer new --file laravel:8.0:mysql demo
	@cd tests-output/demo && php ../bin/producer apply --dry-run --template laravel:8.0:mysql demo

test-apply-dry-run:
	@cd tests-output && rm -fr demo && php ../bin/producer new --file laravel:8.0:mysql demo
	@cd tests-output/demo && php ../bin/producer apply --dry-run --template laravel:8.0:mysql demo

test-apply-only-one-file:
	@rm -fr tests-output/demo && mkdir -p tests-output/demo
	@cp templates/laravel/8.0/mysql.yml tests-output/demo/producer.yml
	@cd tests-output/demo && php ../../bin/producer apply
