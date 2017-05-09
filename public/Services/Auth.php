<?php namespace Services;

//here's a basic example of an authentification service
//services are scripts that will be reused for different pages
//NOTE: /!\ YOU CAN IMPORT SERVICES ON .html FILES WITH THIS SYNTAX -> [import Service[servicename]]
class Auth{
  public $Session;
  public $User;

  public function __construct(){
    switch($_GET["page"]){
      case "/disconnect":
        Session::GetInstance()->DestroyAll();
        header("Location: /login");
        break;
      case "/login":
        if(Session::GetInstance()->GetData("user") !== null){
          header("Location: /home");
        }
        break;
      default:
        if(Session::GetInstance()->GetData("user") === null){
          header("Location: /login");
        }
        else{
          $this->User = Session::GetInstance()->GetData("user");
        }
        break;
    }
  }

  public function Authentificate($data){
    $conn = new DbConnecter();
    if($conn->IsConnected()){
      $statement = new Statement(
        'SELECT user.ID, user.EMAIL, user.PHONE, user.PASSWORD, user.TYPE,
                committee.NAME,
                club.NAME, club.COMMITTEE_ID,
                captain.NAME, captain.LASTNAME, captain.TEAM_ID,
                reporter.NAME, reporter.LASTNAME, reporter.CLUB_ID
        FROM user
        LEFT JOIN committee ON committee.ID = User.ID
        LEFT JOIN club ON club.ID = User.ID
        LEFT JOIN captain ON captain.ID = User.ID
        LEFT JOIN reporter ON reporter.ID = User.ID
        WHERE user.LOGIN = @username OR user.EMAIL = @username;'
      );
      $statement->SetParameter('@username', $data['login']);

      $reader = $conn->Select($statement);
      if($reader->rowCount()){
        if(password_verify($data['password'], $reader->GetValue("PASSWORD"))){
          switch($reader->GetValue("TYPE")){
            case 0:
              $this->User = new Committee($reader->GetValue("ID"), $data["login"]);
              $this->User->Name = $reader->GetValue("NAME");
            break;
            case 1:
              $this->User = new Club($reader->GetValue("ID"), $data["login"]);
              $this->User->Name = $reader->GetValue("NAME");
            break;
            case 2:
              $this->User = new Reporter($reader->GetValue("ID"), $data["login"]);
              $this->User->Name = $reader->GetValue("NAME");
              $this->User->Lastname = $reader->GetValue("LASTNAME");
              $this->User->ClubId = $reader->GetValue("CLUB_ID");
            break;
            case 3:
              $this->User = new Captain($reader->GetValue("ID"), $data["login"]);
              $this->User->Name = $reader->GetValue("NAME");
              $this->User->Lastname = $reader->GetValue("LASTNAME");
              $this->User->TeamId = $reader->GetValue("TEAM_ID");
            break;
          }

          $this->User->Username = $data['login'];
          $this->User->Type = $reader->GetValue("TYPE");
          $this->User->Id = $reader->GetValue("ID");
          $this->User->Email = $reader->GetValue("EMAIL");
          $this->User->Phone = $reader->GetValue("PHONE");

          Session::GetInstance()->SetData("user", $this->User);
          return true;
        }
      } else{
        return false;
      }
    }
    else{
      HtmlWriter::NotifyError("Erreur en provenance du serveur. Contactez l'administrateur");
    }
  }
}

 ?>
