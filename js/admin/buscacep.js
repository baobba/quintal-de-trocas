function atualizacep(cep){
    if ( (cep.length < 9) || (cep.length > 0) ){
      cep = cep.replace(/\D/g,"");
      url="http://cep.correiocontrol.com.br/"+cep+".js";
      s=document.createElement('script');
      s.setAttribute('charset','ISO-8859-1');
      s.src=url;
      document.querySelector('head').appendChild(s);
    }	
}
