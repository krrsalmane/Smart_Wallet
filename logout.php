<?php
session_start();

require_once 'models/User.php';

User::logout();