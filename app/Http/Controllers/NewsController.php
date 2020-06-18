<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\News;
use App\User;

class NewsController extends Controller
{
    public function index(){
        $news= News::orderBy('id', 'DESC')->get();
        return view('admin.news.index',compact('news'));
    }

    public function newsadd(){
        return view('admin.news.create');
    }

    public function newsstore(Request $request){
        $news_name=strtoupper($request->title);
        $news=News::where('title','=',$news_name)->first();
        if(!$news){
            $news1=new News();
            $news1->title=$news_name;
            $news1->description=$request->description;
            $news1->save();
            $this->sendPushToUsers($news_name, $news1->id);
            return back()->with('flash_success','news added successfully...');
        } else {
            return back()->with('flash_error','Already this news exist...');
        }
    }

    public function newsedit($id=null){
        $news=News::findOrFail($id);
        return view('admin.news.edit',compact('news'));
    }

    public function newsupdate(Request $request){
        $id = $request->news_id;
        $news_name=strtoupper($request->title);
        $news1=News::where('id','!=',$id)->where('title','=',$news_name)->first();
        if(!$news1){
            $news=News::findOrFail($id);
            $news->title=$news_name;
            $news->description=$request->description;
            $news->save();
            return back()->with('flash_success','news updated successfully...');
        }
        return back()->with('flash_error','news Already Exist...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CoinType  $CoinType
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {
            $id =  $request->id;
            News::find($id)->delete();
            return back()->with('message', 'News deleted successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'News Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'News Not Found');
        }
    }

    public function disableStatus($id)
    {
        try {
            $Coin = News::findOrFail($id);
            $Coin->status = '0';
            $Coin->save();
            return back()->with('message', 'Updated successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'News Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'News Not Found');
        }
    }

    public function enableStatus($id)
    {
        try {
            $Coin = News::findOrFail($id);
            $Coin->status = '1';
            $Coin->save();
            return back()->with('message', 'Updated successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'News Not Found');
        } catch (Exception $e) {
            return back()->with('flash_error', 'News Not Found');
        }
    }

    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToUsers($msg="Testing message sent from function",$id=5){
      $users = User::where('device_token','!=','nodevice')->where('device_token','!=','fsf34f33f')->get();
      if(count($users) > 0):
        foreach($users as $user):
          $deviceid = $user->device_token;
          $device_type = $user->device_type;
          $data = [
                    "to" => $deviceid,
                    "notification" => [
                        "title" => "LioWallet - News",
                        "body" => $msg,
                        "icon" => "icon.png",
                        "sound" => "Tri-tone"
                      ],
                      "data" => [
                         "url" => "http://lio-app.de/",
                         "news_id" => $id
                      ],
                      "news_id" => $id
                  ];
          $data_string = json_encode($data);
          if($device_type=='ios') {
          $headers = ['Authorization: key=AAAA2c41cnA:APA91bEqnAWayl1WPc29eBoGplcNxTEMvfJZpjE-wA0bqud4txiYXu6_08zH67NFRzIsd8J7x0ohvjPUvS07D0w12xLqR6CmzX7OS6liCSgTE6Lp3JzUlArvBuphCeyIwi2w8Sxiszyr',
               'Content-Type: application/json'];
          } else {
            $headers = ['Authorization: key=AAAA7ttdvME:APA91bFEVMnrHApzYjNr4okBsy5XimKDkRhDtfQ7uTAuTQPd7UthY2H-c9fi1TS2PoPK2BbJuSMmY3eKyaGfs4DuSFBazQ75NyNbp6EfZ7bWmf6y4QblvD6FJWt--pXra9IAQkSmTgZ2',
                 'Content-Type: application/json'];
          }
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);
          $result = curl_exec($ch);
          curl_close ($ch);
          print_r($result);
        endforeach;
      endif;
      return true;
    }

    /**
     * Sending Push to a user Device.
     *
     * @return void
     */
    public function sendPushToUser($msg="Testing message sent from function",$id=5){
      // $users = User::where('device_token','!=','nodevice')->where('device_token','!=','fsf34f33f')->get();
      // if(count($users) > 0):
      //   foreach($users as $user):

          $deviceid = 'c2EBsH9iSVG3IKAd11pGnd:APA91bEJyPgvJ9zLC2BGU2mXBT5CY1fdGLp1Z2P8FqaC-HvThCCTcjQUFskAZay6fXH30dLefsrldByu39PdcvL7hC62nzK7f4W5BJ3ztAwPvzVVWXqyK48JCntjDEC38w44AMCug8LD';;
          $device_type = 'android';
          $data = [
                    "to" => $deviceid,
                    "notification" => [
                        "title" => "LioWallet - News",
                        "body" => $msg,
                        "icon" => "icon.png",
                        "sound" => "Tri-tone"
                      ],
                      "data" => [
                         "url" => "http://lio-app.de/",
                         "news_id" => $id
                      ],
                      "news_id" => $id
                  ];
          $data_string = json_encode($data);
          if($device_type=='ios') {
          $headers = ['Authorization: key=AAAA2c41cnA:APA91bEqnAWayl1WPc29eBoGplcNxTEMvfJZpjE-wA0bqud4txiYXu6_08zH67NFRzIsd8J7x0ohvjPUvS07D0w12xLqR6CmzX7OS6liCSgTE6Lp3JzUlArvBuphCeyIwi2w8Sxiszyr',
               'Content-Type: application/json'];
          } else {
            $headers = ['Authorization: key=AAAA7ttdvME:APA91bFEVMnrHApzYjNr4okBsy5XimKDkRhDtfQ7uTAuTQPd7UthY2H-c9fi1TS2PoPK2BbJuSMmY3eKyaGfs4DuSFBazQ75NyNbp6EfZ7bWmf6y4QblvD6FJWt--pXra9IAQkSmTgZ2',
                 'Content-Type: application/json',
               'SenderId:1025882569921'];
          }
          //dd($headers);
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);
          $result = curl_exec($ch);
          curl_close ($ch);
          dd($result);
      //   endforeach;
      // endif;
      // return true;
      // $deviceid = 'fMuq1DtvJkVspP1c_V3k47:APA91bGOp_GOLqcVAuhyfUEfTp7tXjyZncwiqyYHZTd8xBJfp2xd96UUK_7eurjBBcB2EHwbAOXx6NcioQyfSpDEtoam3kQMyxPVPDOs0ZPPL_FmeuUhAiHU3JWWKuzzOg-Uo0Nbdl4A';
      // $device_type = 'ios';
      // try{
      //   //$optionBuilder = new OptionsBuilder();
      //   $option = ''; //$optionBuilder->build();
      //
      //   $dataBuilder = new PayloadDataBuilder();
      //   $dataBuilder->addData([
      //   	'news_id' => $id
      //   ]);
      //   $data = $dataBuilder->build();
      //
      //   $notificationBuilder = new PayloadNotificationBuilder();
      //   $notificationBuilder->setTitle('LioWallet')
      //               		->setBody('News Item Added: '.$msg)
      //               		->setBadge('First');
      //
      //   $notification = $notificationBuilder->build();
      //
      //   $response = FCM::sendTo($deviceid, $option, $notification, $data);
      //   dd($response);
      // } catch(Exception $e){
      //   return $e;
      // }
      // try{
      //   $data = [
      //             "to" => $deviceid,
      //             "notification" => [
      //                 "title" => "LioWallet - News",
      //                 "body" => $msg,
      //                 "icon" => "icon.png",
      //                 "sound" => "Tri-tone"
      //               ],
      //               "data" => [
      //                  "url" => "http://lio-app.de/",
      //                  "news_id" => $id
      //               ],
      //               "news_id" => $id
      //           ];
      //   $data_string = json_encode($data);
      //   if($device_type=='ios') {
      //   $headers = ['Authorization: key=AAAA2c41cnA:APA91bEqnAWayl1WPc29eBoGplcNxTEMvfJZpjE-wA0bqud4txiYXu6_08zH67NFRzIsd8J7x0ohvjPUvS07D0w12xLqR6CmzX7OS6liCSgTE6Lp3JzUlArvBuphCeyIwi2w8Sxiszyr',
      //        'Content-Type: application/json'];
      //   } else {
      //     $headers = ['Authorization: key=AAAA7ttdvME:APA91bFEVMnrHApzYjNr4okBsy5XimKDkRhDtfQ7uTAuTQPd7UthY2H-c9fi1TS2PoPK2BbJuSMmY3eKyaGfs4DuSFBazQ75NyNbp6EfZ7bWmf6y4QblvD6FJWt--pXra9IAQkSmTgZ2',
      //          'Content-Type: application/json'];
      //   }
      //   $ch = curl_init();
      //   curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      //   curl_setopt( $ch,CURLOPT_POST, true );
      //   curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      //   curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      //   curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);
      //   $result = curl_exec($ch);
      //   curl_close ($ch);
      //   //dd($result);
      // } catch(Exception $e){
      // //  dd($e);
      //    return $e;
      // }
    }
}
