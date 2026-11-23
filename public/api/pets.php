<?php
require_once __DIR__ . '/../../src/Pet.php';
require_once __DIR__ . '/../../src/CrudController.php';

(new CrudController(new Pet()))->tratar();
