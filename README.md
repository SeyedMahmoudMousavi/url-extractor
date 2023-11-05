# UrlExtractor
Extract urls from your a file or web address

## install

        composer require codecrafted/url-extractor

### Full expamle :

        require_once 'vendor/autoload.php';

        use  Codecrafted\UrlExtractor\URL;

        $links = new URL();
        $limit = 50;                            // count of each address you give

        //$url = "https://www.youtube.com";
        // $links->extractURL($url,$limit);

        $urls = [
                "https://www.youtube.com",
                "https://github.com/SeyedMahmoudMousavi"
        ];
        $links->extractURL($url,$limit);

        $all_url_limtt = 50;                    // how many link you want to export
        $links->limit($all_url_limtt);          // how many link you want to export

        //$links->fileOnly();                   // export only files

        $fileTypes = ['.jpg','mp3'];            // your favorite file types
        $links->fileOnly($fileTypes);

        $links->sortURL();                      // sort links asc
        //$links->sortURL(true);                // sort links desc

        $links->showAsHTML();                   // show in web page
        $string_urls = $links->showURL();       // return as string
        $array_urls = $links->getURL();         // return as array     

### you can write your data in the file by using this code and library

        use Codecrafted\IronElephant\File;

        $f = new File();

        $data = $links->showURL();
        $f->write($data, 'links.txt');
