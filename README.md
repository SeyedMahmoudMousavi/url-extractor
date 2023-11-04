# UrlExtractor
Extract urls from your a file or web address

## install
        composer require codecrafted/url-extractor
## use

        use  Codecrafted\UrlExtractor\URL;

        $links = new URL();
        $url = "name of your file or address";
        $limit = 5;              // count of each url you need, default is limitless
        $links->extractURL($url, $limit);
or :

        use  Codecrafted\UrlExtractor\URL;

        $links = new URL();
        $urls = [
                "name of your first file or address",
                "name of your secound file or address"
        ];
        $limit = 5;              // count of each url you need, default is limitless
        $links->extractURL($url, $limit);

### Full expamle :

        require_once './src/URL.php';
        require_once 'vendor/autoload.php';

        use  Codecrafted\UrlExtractor\URL;

        $links = new URL();
        $limit = 50; // count of each address you give

        //$url = "https://www.youtube.com";
        // $links->extractURL($url,$limit);

        $urls = ["https://www.youtube.com","https://github.com/SeyedMahmoudMousavi"];
        $links->extractURL($url,$limit);

        all_url_limtt = 50;             // how many link you want to export
        $links->limit(50);              // how many link you want to export

        //$links->fileOnly();           // export only files
        $fileTypes = ['.jpg','mp3'];    // your favorite file types
        $links->fileOnly($fileTypes);

        $links->showAsHTML();                   // show in web page
        $string_urls = $links->showURL();       // return as string
        $array_urls = $links->getURL();         // return as array     

### you can write your data in the file by using this code and library

        use Codecrafted\IronElephant\File;

        $f = new File();

        $data = $links->showURL();
        $f->write($data, 'links.txt');
