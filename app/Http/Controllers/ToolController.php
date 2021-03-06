<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkHotMail(Request $request)
    {
        $result = [];
        $arr_mail = explode("\n", $request->mail);
        foreach($arr_mail as $mail){
            $result[] = $this->checkMail($mail);
        }
        return response()->json([
            'data' => $result
        ]);
    }

    private function checkMail($mail){
        $data['opid'] = '302EE8316C3CEC9A';
        $data['consentBumpParams'] = json_decode($consentBumpParams);
        $data['params'] = 'EgZ2aWRlb3MYAyAAMAE%3D';
        $data['browseId'] = $channelId;
        $data = '{"username":"'.$mail.'","uaid":"678b494a26cb45c0bfda16b66a875416","isOtherIdpSupported":false,"checkPhones":false,"isRemoteNGCSupported":true,"isCookieBannerShown":false,"isFidoSupported":true,"forceotclogin":false,"otclogindisallowed":false,"isExternalFederationDisallowed":false,"isRemoteConnectSupported":false,"federationFlags":3,"isSignup":false,"flowToken":"DQf9xUg8NbW7CIldvbHBn6p17!gqknE45mWi7HHKBLASer392e8NROXp5siyAKreOr4G2phlTQSujAKThCssFJ1kQ8hklefIVdj*QNM97LOS4zevd4lDKLisfcjbXeVyCG6L3Kcd1QjRrJNH0HVfRxspVDKMkij1r8rj5v13OcZ5!QXLgi!Ko0txP9wpc80UzP0Zlywfq63KmJ9lPaAeVYwa*yRIqfTOzDaorwOhaUgWnibeBTFX40BZRvDBsgWRRQ$$"}';
        try {
            $client = new Client([
                'headers' => ['Content-Type' => 'application/json; charset=UTF-8']
              ]);
            $url = "https://login.live.com/GetCredentialType.srf?opid=302EE8316C3CEC9A&id=38936&uiflavor=web&mkt=EN-US&lc=1033&uaid=678b494a26cb45c0bfda16b66a875416";
            $response = $client->request('POST', $url, [
                'body' => $data
              ]);
            $mailInfo = $response->getBody()->getContents();
            dd($mailInfo);
            $imgInfo = json_decode($mailInfo, true);
            return $imgInfo;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
