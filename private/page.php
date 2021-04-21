<?php
require_once "lib/Web/Webpack.php";
require_once "lib/Web/Ticket.php";
require_once "lib/Web/Session.php";
use \Web\Ticket;
use \Web\SessionDefault;
class page {
    public static function vuePage(string $script, array $args = [], &$head, &$body): void {
        function encodeMetaData(array $metadata, int $expire = 60): string {
            $key = hash("sha256", date("YmdHis") . (string)openssl_random_pseudo_bytes(40) . uniqid());
            $md = $metadata;
            $md["exp"] = time() + $expire;
            $token = \Firebase\JWT\JWT::encode($md, $key);
            return $token . "|" . $key;
        }
        $md = array_merge([ "__TICTKE__" => (new Ticket(new SessionDefault()))->save() ], $args);
        $str = encodeMetaData($md);
        \Web\webpack(ROOT, "css", "js", $script, $head, $body);
        $head = '<meta name="backend" content="'.$str.'">'.PHP_EOL.$head;
    }
}