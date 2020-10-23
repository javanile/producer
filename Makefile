#!make

test-new-from-local-file:
	@cd tests-output && rm -fr demo && php ../bin/producer new --file ../templates/8.0/jetstream-inertia.yml demo

test-new-from-template:
	@cd tests-output && rm -fr demo php ../bin/producer new --template laravel:8.0:jetstream-inertia demo
