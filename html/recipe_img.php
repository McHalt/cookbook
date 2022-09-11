<?php

$possiblePath = "Files/Imgs/recipes/" . ($_GET['recipe_id'] ?? -1);

if (file_exists($possiblePath . ".jpg")) {
	header("Location: $possiblePath.jpg");
} elseif(file_exists($possiblePath . ".png")) {
	header("Location: $possiblePath.png");
}else {
	header("Location: https://picsum.photos/500/500");
}