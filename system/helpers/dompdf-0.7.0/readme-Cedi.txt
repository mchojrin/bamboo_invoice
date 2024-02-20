
get the latest stable version from github https://github.com/dompdf/dompdf/
select via tag which version you would like to download

extract to <target-folder>

create <target-folder>/composer.json

{
    "name": "dompdf/dompdf",
    "type": "library",
    "description": "DOMPDF is a CSS 2.1 compliant HTML to PDF converter",
    "homepage": "https://github.com/dompdf/dompdf",
    "license": "LGPL-2.1",
    "autoload": {
        "psr-4" : {
            "Dompdf\\" : "src/"
        },
        "classmap" : ["lib/"]
    },
    "require": {
        "php": ">=5.3.0",
        "ext-gd": "*",
        "ext-dom": "*",
        "ext-mbstring": "*",
        "phenx/php-font-lib": "0.4.*",
        "phenx/php-svg-lib": "0.1.*"
    },
    "require-dev": {
        "phpunit/phpunit": "3.7.*"
    },
    "extra": {
        "branch-alias": {
            "dev-develop": "~0.7"
        }
    }
}

run in cmd shell
<target-folder>/composer update


this will get you in <target-folder>/vendor/phenx
php-font-lib and php-svg-lib 
copy the two lib folders into your int <target-folder>/lib

delete folder <target-folder>/vendor/
delete folder <target-folder>/tests/

source code - function should look like this.

use Dompdf\Dompdf;
function pdf_create($html, $filename, $stream=TRUE) 
{
    // Composer's auto-loading functionality
    require __DIR__ . "/dompdf-0.7.0/autoload.inc.php";

    $dompdf = new Dompdf();
    $dompdf->set_paper("a4", "portrait");
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename);
    }
    write_file("./invoices_temp/$filename", $dompdf->output());
}


