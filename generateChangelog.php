<?php

use DigiLive\GitChangelog\Renderers\MarkDown;

require 'vendor/autoload.php';

$changelogOptions = [
    'headTagName' => '1.8.0',
    'headTagDate' => '2025-10-04',
    'titleOrder' => 'ASC',
];
$changelogLabels = ['Add', 'Cut', 'Fix', 'Bump', 'Document','Optimize'];


$changeLog = new MarkDown();
$changeLog->setUrl('commit', 'https://github.com/JBlond/math-function/commit/{commit}');
$changeLog->setUrl('issue', 'https://github.com/JBlond/math-functions/issues/{issue}');

try {
    $changeLog->setOptions($changelogOptions);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
$changeLog->setLabels(...$changelogLabels);
try {
    $changeLog->build();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
file_put_contents('changelog.md', $changeLog->get());
