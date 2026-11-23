<?php
require_once __DIR__ . '/../../src/Cliente.php';
require_once __DIR__ . '/../../src/CrudController.php';

(new CrudController(new Cliente()))->tratar();
