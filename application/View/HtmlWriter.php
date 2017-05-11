<?php namespace View;

class HtmlWriter{
  public static function Redirect($url){
    echo "<script>window.location.href = '" . $url . "';</script>";
  }
  public static function DataToJS($varName, $data){
    echo "<script>var " . $varName . " = JSON.parse('" . json_encode($data) ."');</script>";
  }
  public static function ClientLogStyle($content, $style){
    if(!$GLOBALS["ENV_PRODUCTION"]){
      $content = str_replace(['\\', "'"], ['\\\\', "\\'"], $content);
      echo "<script>console.log('%c " . $content . "', '" . $style . "');</script>";
    }
  }
  public static function ClientLog($content){
    if(!$GLOBALS["ENV_PRODUCTION"]){
      $content = str_replace(['\\', "'", '"'], ['\\\\', "\\'", ], $content);
      echo "<script>console.log('" . $content . "');</script>";
    }
  }
  public static function Notify($content){
    self::ClientLog($content);
    $content = str_replace(['\\', "'", '"'], ['\\\\', "\\'", ], $content);
    echo "<script>
            window.addEventListener('load', function(){
              var notify = document.createElement('div');
              notify.className = 'notify-success';
              notify.innerHTML = '". $content . "';
              document.body.appendChild(notify);
            });
          </script>";
  }
  public static function NotifyError($content){
    self::ClientLogStyle($content, "color: white; background-color: red;");
    $content = str_replace(['\\', "'", '"'], ['\\\\', "\\'", ], $content);
    echo "<script>
            window.addEventListener('load', function(){
              var notify = document.createElement('div');
              notify.className = 'notify-error';
              notify.innerHTML = '". $content . "';
              document.body.appendChild(notify);
            });
          </script>";
  }
}

 ?>
