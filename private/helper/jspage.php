<?php

class JsPage {
    private array $cssl = [];
    private array $jsl = [];
    private $data = null;
    private $metaList = [
        (object)[ "charset"=>"utf-8" ],
        (object)[ "httpEquiv" => "X-UA-Compatible", "content"=>"IE=edge" ],
        (object)[ "name" => "viewport", "content" => "width=device-width, initial-scale=1" ]
    ];
    private string $title = '';
    public function __construct(string $title = "") {
        $this->title = $title;
    }

    public function clearMeta() : JsPage {
        $this->metaList = [];
        return $this;
    }

    public function addMeta(stdClass $meta) : JsPage {
        array_push($this->meta,$meta);
        return $this;
    }

    public function css(string $name) : JsPage {
        array_push($this->cssl, $name);
        return $this;
    }

    public function js(string $name) : JsPage {
        array_push($this->jsl, $name);
        return $this;
    }

    public function data($data) : JsPage {
        $this->data = $data;
        return $this;
    }

    public function show() : void {
        $data = json_encode(
            ["js" => $this->jsl,
            "css" => $this->cssl,
            "data" => $this->data,
            "title"=>$this->title,
            "meta" => $this->metaList,
        ]);
?><?php echo "//"; ?><script>
            window.addEventListener('load', (event) => {
                var list = <?php echo $data; ?>;
                var rnd = Math.random().toString(36).substring(7);
                var head = document.getElementsByTagName('head')[0];
                document.title = data.title;
                document.body.innerHTML = "";
                var container = document.createElement("div");
                container.id = "app";
                document.body.appendChild( container );
                document.body.style.margin = "0";
                document.body.style.padding = "0";
                window["__DATA__"] = list.data;
                for ( var i=0; i < list.meta.length; i++ ) {
                    var fileref = document.createElement("meta");
                    for( var k in meta ) {
                        fileref[k] = meta[k];
                    }
                    head.appendChild(fileref);
                }
                for (var i = 0; i < list.css.length; i++) {
                    var fileref = document.createElement("link");
                    fileref.setAttribute("rel", "stylesheet");
                    fileref.setAttribute("type", "text/css");
                    fileref.setAttribute("href", list.css[i] + "?" + rnd);
                    head.appendChild(fileref);
                }
                for (var i = 0; i < list.js.length; i++) {
                    var fileref = document.createElement('script');
                    fileref.setAttribute("type", "text/javascript");
                    fileref.setAttribute("src", list.js[i] + "?" + rnd);
                    head.appendChild(fileref);
                }
            });
            <?php echo "//"; ?></script>
<?php
    }
}
