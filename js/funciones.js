function ShowLDAP(what) {
    Limpia();
    $("#LDAPGroups").hide();
    $("#SmbLDAPGroups").hide();
    $("#SrchLDAPGp").hide();
    if (what == "LDAPUsers") {
        $("#LDAPUser").show();
    }
    if (what == "LDAPDevUsers") {
        $("#LDAPDevUser").show();
    }
    if (what == "AddLDAPCell") {
        $("#AddLDAPCell").show();
        $.ajax({
            type: "POST",
            url: 'php/NewCell.php',
            dataType: "json",
            success: function(data) {
                if (data[0].success == "YES") {
                    $('#AddLDAPCell').html(data[0].data);
                }
            }
        });
    }
    if (what == "AddLDAPUsers") {
        $.ajax({
            type: "POST",
            url: 'php/NewUser.php',
            dataType: "json",
            success: function(data) {
                if (data[0].success == "YES") {
                    $('#NewLDAPUser').html(data[0].data);
                }
            }
        });
    }
    if (what == "NukeDev") {
        var html ='<div class="col-lg-12"><div class="card"><div class="card-body"><div class="form-validation"><input type="text" id="srchp" class="form-control" ><br><input type="checkbox" id="confirm" name="confirm" disabled>Realizar cambios<br><button type="button" class="btn btn-primary mb-2" onclick="SrchParam()">Buscar</button><div id="DevQResult"></div></div></div></div>';
        $('#NewLDAPUser').html(html);
    }
    if (what == "AddLDAPDevUsers") {
        $.ajax({
            type: "POST",
            url: 'php/NewDevUser.php',
            dataType: "json",
            success: function(data) {
                if (data[0].success == "YES") {
                    $('#NewLDAPDevUser').html(data[0].data);
                }
            }
        });
    }
}

function Limpia() {
        $('#TOPDIV').html('');
        $('#NewLDAPUser').html('');
        $('#NewLDAPDevUser').html('');
        $("#LDAPUser").hide();
        $("#LDAPDevUser").hide();
        $("#AddLDAPCell").hide();
        $("#LDAPAlias").hide();
        $('#VPNTable').html('');
        $('#BOTTDIV').html('');
        $('#MEDDIV').html('');
        $('#teibol').html('');
        $('#LDAPGroups').hide(); 
        $('#SMBLDAPGroups').hide();
        $("#SmbSrchLDAPGp").hide();
       $("#SrchLDAPGp").hide();
}

function respa() {
    var user = document.getElementById("user").value;
    var src = document.getElementById("src").value;
    var ps="dunno";
    var psb="dunno";
    if (src == "ps") {
        var ps = document.getElementById("passa").value;
        var psb = document.getElementById("passb").value;
    }
    //alert(src);
    $.ajax({
        type: "POST",
        url: 'php/ResetPass.php',
        data: { user: user, src: src,ps: ps, psb: psb },
        dataType: "json",
        success: function(data) {
            if (data[0].success == "YES") {
                alert('Password Actualizado');
            } 
            if (data[0].success == "ASKPASS") {
                $('#thapass').html(data[0].html);
                $('#src').attr('value', 'ps');
            } 
            if (data[0].success == "NOEXISTE") {
                alert('Usuario no existe');
            } 
            if (data[0].success == "NOTRESETASK") {
                alert('El H. Departamento De Sistemas no ha autorizado el reset del password');
            } 
            if (data[0].success == "DONTMATCH") {
                alert('Password no coinciden');
            } 
            if (data[0].success == "WEAK") {
                alert('Debe ser de 8 caracteres, debe contener al menos una letra Mayuscula, un numero y un caractrer especial');
            } 
        }
    });
}    

function SaveDevCellNumber(tag) {
    alert(tag);
    id='inputnumber'+tag;
    val = document.getElementById(id).value;
    alert(val);
    $.ajax({
        type: "POST",
        url: 'php/ChangeCellNumber.php',
        data: { tag: tag, val: val  },
        dataType: "json",
        success: function(data) {
            if (data[0].success == "NO") {
                alert(data[0].data);
            }
            if (data[0].success == "YES") {
                alert(data[0].data);
                id='#divcell'+tag;
                $(id).html(val); 
            }
        }
    });
}

function SmbGrpMemberAction (valor,dn,accion) {
    var user = document.getElementById("newsmbgrpuser").value;
    $.ajax({
        type: "POST",
        url: 'php/ChangeSmbLDAPGrp.php',
        data: { dn: dn, accion: accion, user: user, valor: valor  },
        dataType: "json",
        success: function(data) {
            if (data[0].success == "NO") {
                alert(data[0].data);
            }
            if (data[0].success == "YES") {
                alert(data[0].data);
            }
        }
    });
}    

function DeleteUser(dn) {
    var r = confirm("Esta seguro que quiere borrar a "+dn+"????");
    if (r == true) {
        var rr = confirm("Esta seguro que quiere borrar a "+dn+"???? lo jura por la mejor artista de este mundo, Shania Twain?");
        if (rr == true) {
            $.ajax({
                type: "POST",
                url: 'php/DeleteUser.php',
                data: { dn: dn },
                dataType: "json",
                success: function(data) {
                    if (data[0].success == "YES") {
                        alert('Usuario BORRADO');
                        ShowLDAP('LDAPUsers');
                    } else {
                        alert(data[0].success);
                    }
                }
            });
        } else {
          alert("Abusado!!");
        }
    } else {
      alert("Abusado!!");
    }
    return false;
}

function ShowOPENVPN() {
    Limpia();
    $.ajax({
            type: "POST",
            url: 'php/ShowOPENVPN.php',
            //data: { what: what },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
            }
                if (data[0].success == "YES") {
                $('#VPNTable').html(data[0].data);
                $('#TablaVPN').dataTable( { "lengthMenu": [[150, -1], [150, "All"]]  } );
            }
        }
    });
}

function GetLastAvailTag() {
    var ofi = document.getElementById("ofi").value;
    alert(ofi);
    $.ajax({
        type: "POST",
        url: 'php/CalculateLastTag.php',
        data: { ofi: ofi },
        dataType: "json",
        success: function(data)
            {
            if (data[0].success == "NO") {
                alert(data[0].error);
            }
                if (data[0].success == "YES") {
                $('#TOPDIV').html(data[0].data);
                $('#teibol').dataTable();
            }
        }
    });
}

function ShowLastTag() {
    Limpia();
    $.ajax({
        type: "POST",
        url: 'php/ShowLastTag.php',
        data: {  },
        dataType: "json",
        success: function(data) {
            if (data[0].success == "NO") {
                alert(data[0].error);
            }
            if (data[0].success == "YES") {
                $('#TOPDIV').html(data[0].data);
                $('#teibol').dataTable();
            }
        }
    });
}

function ShowTravel() {
    Limpia();
     $.ajax({
        type: "POST",
        url: 'php/ShowTravellers.php',
        data: {  },
        dataType: "json",
        success: function(data) {
            if (data[0].success == "NO") {
                alert(data[0].error);
            }
            if (data[0].success == "YES") {
                $('#TOPDIV').html(data[0].data);
                $('#teibol').dataTable();
            }
        }
    });
}

function ShowHtml(what) {
    $("#TOPDIV").load('http://192.168.120.173/term/temp/index.php');
}

function Show(what,where) {
    Limpia();
    $.ajax({
        type: "POST",
        url: 'php/Show'+what+'.php',
        data: { where: where, what: what },
        dataType: "json",
        beforeSend: function() {
            $('#TOPDIV').html('');
            $("#loaderDiv").show();
        },
        success: function(data) {
            $("#loaderDiv").hide();
            if (what =='print') {
                alert(data[0].errorconn);
            }
            if (data[0].success == "NO") {
                alert(data[0].error);
                $('#TOPDIV').html('<div class="card"><div class="card-header"><div class="card-body">'+where+' '+data[0].error+'</div></div></div>');
            }
            if (data[0].success == "YES") {
                $('#TOPDIV').html(data[0].data);
                $('#teibol').dataTable( { "lengthMenu": [[50, -1], [50, "All"]]  } );
            }
        }
    });
}


function SrchParam() {
    var param = document.getElementById("srchp").value;
    var action = 'DUNNO';
    if ($("#confirm").is(':checked')) {
        action = 'CHANGE';
    }
    alert(action);
    $.ajax({
        type: "POST",
        data: { param: param, action: action },
        url: 'php/ProcessDevSrch.php',
        dataType: "json",
        success: function(data) {
            if (data[0].success == "YES") {
                $('#BOTTDIV').html(data[0].mes);
                $('#confirm').removeAttr("disabled");
            }
        }
    });
}

function ShowCells(what) {
    Limpia();
    $("#LDAPGroups").hide();
    $("#SmbLDAPGroups").hide();
    $("#SrchLDAPGp").hide();
    $.ajax({
        type: "POST",
        url: 'php/GetCellTable.php',
        dataType: "json",
        success: function(data) {
            if (data[0].success == "YES") {
                $('#NewLDAPUser').html(data[0].data);
                $('#celltable').dataTable( { "lengthMenu": [[150, -1], [150, "All"]]  } );
            }
        }
    });
}

function ShowLDAPG(tipo) {
    Limpia();
    if (tipo == "LDAPGroup") {
        $("#LDAPGroups").show();    
    }
    if (tipo == "SMBLDAPGroup") {
        $("#SMBLDAPGroups").show();    
    }
}

function LoadGroupQuery(type) {
    Limpia();
    $("#SrchLDAPGp").show();
    document.getElementById("GrpSrchTip").value = type;
    $('#encabezadobusq').html('Buscar '+type);
}

function SmbLoadGroupQuery(type) {
    Limpia();
    $("#SmbSrchLDAPGp").show();
    document.getElementById("smbGrpSrchTip").value = type;
    $('#smbencabezadobusq').html('Buscar '+type);
}

function selectUserGrp(user) {
    var grp = document.getElementById("SelectedGroup").value;
    $.ajax({
        type: "POST",
        url: 'php/AddUserToGroup.php',
        data: { user: user, grp: grp },
        dataType: "json",
        beforeSend: function() {
            $('#TOPDIV').html('');
            $("#loaderDiv").show();
        },
        success: function(data) {
            $("#loaderDiv").hide();
            if (data[0].success == "NO") {
                alert(data[0].error);
            }
            if (data[0].success == "YES") {
                alert('usuario agregado');
            }
        }
    });
    return false;
}

function selectDevUser(user) {
    $("#LDAPDevUser").hide();
    $('#TOPDIV').html('Trabajando con '+user);
    $.ajax({
        type: "POST",
        url: 'php/ShowDevUser.php',
        data: { user: user },
        dataType: "json",
        beforeSend: function() {
            $('#TOPDIV').html('');
            $("#loaderDiv").show();
        },
        success: function(data) {
            $("#loaderDiv").hide();
            if (data[0].success == "NO") {
                alert(data[0].error);
                $('#TOPDIV').html('<div class="card"><div class="card-header"><div class="card-body">'+where+' '+data[0].error+'</div></div></div>');
            }
            if (data[0].success == "YES") {
                if (data[0].alertlan == "YES") {
                    alert('LAN MAC NO DECLARADA, EL USUARIO FUE MAL DADO DE ALTA, FAVOR DE PONER VALOR O DAR VALOR NO SI NO TIENE MAC');
                }
                if (data[0].alertwifi == "YES") {
                    alert('WIFI MAC NO DECLARADA, EL USUARIO FUE MAL DADO DE ALTA, FAVOR DE PONER VALOR O DAR VALOR NO SI NO TIENE MAC');
                }
                $('#MEDDIV').html(data[0].data);
                $('#teibol').dataTable();
                $('[data-toggle="tooltip"]').tooltip();
                var actions = $("table td:last-child").html();
                $(".add-new").click(function(){
                    $(this).attr("disabled", "disabled");
                    var index = $("table tbody tr:last-child").index();
                    var row = '<tr>' +
                    '<td><input type="text" class="form-control" name="newvalue" id="newvalue"></td>' +
                    '<td>' + actions + '</td>' +
                    '</tr>';
                    $("table").append(row);
                    $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
                    $('[data-toggle="tooltip"]').tooltip();
                });
                $(document).on("click", ".add", function(){
                    var empty = false;
                    var input = $(this).parents("tr").find('input[type="text"]');
                    input.each(function(){
                        if(!$(this).val()){
                            $(this).addClass("error");
                            empty = true;
                        } else {
                            $(this).removeClass("error");
                        }
                    });
                    $(this).parents("tr").find(".error").first().focus();
                    if(!empty){
                        input.each(function(){
                            $(this).parent("td").html($(this).val());
                        });
                        $(this).parents("tr").find(".add, .edit").toggle();
                        $(".add-new").removeAttr("disabled");
                    }
                });
                $(document).on("click", ".delete", function(){
                    $(this).parents("tr").remove();
                    $(".add-new").removeAttr("disabled");
                });
            }
        }
    });
}

function selectUser(user) {
    $("#LDAPUser").hide();
    $('#TOPDIV').html('Trabajando con '+user);
    $.ajax({
        type: "POST",
        url: 'php/ShowUser.php',
        data: { user: user },
        dataType: "json",
        beforeSend: function() {
            $('#TOPDIV').html('');
            $("#loaderDiv").show();
        },
        success: function(data) {
            $("#loaderDiv").hide();
            if (data[0].success == "NO") {
                alert(data[0].error);
                $('#TOPDIV').html('<div class="card"><div class="card-header"><div class="card-body">'+where+' '+data[0].error+'</div></div></div>');
            }
            if (data[0].success == "YES") {
                if (data[0].alertlan == "YES") {
                    alert('LAN MAC NO DECLARADA, EL USUARIO FUE MAL DADO DE ALTA, FAVOR DE PONER VALOR O DAR VALOR NO SI NO TIENE MAC');
                }
                if (data[0].alertwifi == "YES") {
                    alert('WIFI MAC NO DECLARADA, EL USUARIO FUE MAL DADO DE ALTA, FAVOR DE PONER VALOR O DAR VALOR NO SI NO TIENE MAC');
                }
                $('#MEDDIV').html(data[0].data);
                $('#teibol').dataTable();
                $('[data-toggle="tooltip"]').tooltip();
                var actions = $("table td:last-child").html();
                $(".add-new").click(function(){
                    $(this).attr("disabled", "disabled");
                    var index = $("table tbody tr:last-child").index();
                    var row = '<tr>' +
                    '<td><input type="text" class="form-control" name="newvalue" id="newvalue"></td>' +
                    '<td>' + actions + '</td>' +
                    '</tr>';
                    $("table").append(row);
                    $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
                    $('[data-toggle="tooltip"]').tooltip();
                });
                $(document).on("click", ".add", function(){
                    var empty = false;
                    var input = $(this).parents("tr").find('input[type="text"]');
                    input.each(function(){
                        if(!$(this).val()){
                            $(this).addClass("error");
                            empty = true;
                        } else{
                            $(this).removeClass("error");
                        }
                    });
                    $(this).parents("tr").find(".error").first().focus();
                    if(!empty){
                        input.each(function(){
                            $(this).parent("td").html($(this).val());
                        });
                        $(this).parents("tr").find(".add, .edit").toggle();
                        $(".add-new").removeAttr("disabled");
                    }
                });
                $(document).on("click", ".delete", function(){
                    $(this).parents("tr").remove();
                    $(".add-new").removeAttr("disabled");
                });
            }
        }
    });
}

function selectGroup(group,tipo) {
    $("#LDAPUser").hide();
    $("#LDAPAlias").hide();
    $('#TOPDIV').html('Trabajando con '+group+' -> '+tipo);
    $.ajax({
            type: "POST",
            url: 'php/ShowGrpEdit.php',
            data: { group: group, tipo: tipo },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
                alert(data[0].error);
                $('#TOPDIV').html('<div class="card"><div class="card-header"><div class="card-body">'+where+' '+data[0].error+'</div></div></div>');
            }
                if (data[0].success == "YES") {
                $('#BOTTDIV').html(data[0].data);
                $('#teibol').dataTable();
            }
        }
    });
}

function SmbselectGroup(group,tipo) {
    $("#smbLDAPUser").hide();
    $("#LDAPAlias").hide();
    $('#TOPDIV').html('Trabajando con '+group+' -> '+tipo);
    $.ajax({
            type: "POST",
            url: 'php/smbShowGrpEdit.php',
            data: { group: group, tipo: tipo },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
                alert(data[0].error);
                $('#TOPDIV').html('<div class="card"><div class="card-header"><div class="card-body">'+where+' '+data[0].error+'</div></div></div>');
            }
                if (data[0].success == "YES") {
                $('#BOTTDIV').html(data[0].data);
                $('#teibol').dataTable();
            }
        }
    });
}

function ValidateLDAPass() {
    var pas = document.getElementById("val-userpassword").value;
    var passwd = document.getElementById("passwd").value;
    var user = document.getElementById("val-uid").value;
    $.ajax({
        type: "POST",
        url: 'php/ValidatePass.php',
        data: { pas: pas, passwd: passwd, user: user },
        dataType: "json",
        beforeSend: function() {
            $('#TOPDIV').html('');
            $("#loaderDiv").show();
        },
        success: function(data) {
            $("#loaderDiv").hide();
            if (data[0].success == "NO") {
                alert('INCORRECTO');
            }
            if (data[0].success == "YES") {
                alert('CORRECTO');
            } else {
                alert(data[0].success);
            }
        }
    });
}

function ResetLDAPass() {
    var pas = document.getElementById("val-userpassword").value;
    var passwd = document.getElementById("passwd").value;
    var user = document.getElementById("val-uid").value;
    $.ajax({
            type: "POST",
            url: 'php/ResetPass.php',
            data: { pas: pas, passwd: passwd, user: user },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
                alert('INCORRECTO');
            }
                if (data[0].success == "YES") {
                    alert('CORRECTO');
            } else {
                alert(data[0].success);
            }

           }
       });
}

function EnableService(dn,nuevo,initial) {
    if (nuevo == "Drupal") {
        $.ajax({
            type: "POST",
            url: 'php/UpdateService.php',
            data: { dn: dn, nuevo: nuevo, initial: initial },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
                    alert('INCORRECTO');
                }
                if (data[0].success == "YES") {
                    alert('CAMBIO CORRECTO');
                } else {
                    alert(data[0].success);
                }
            }
        });
    }
    if (nuevo == "OpenVPN") {
        $.ajax({
            type: "POST",
            url: 'php/UpdateOpenvpn.php',
            data: { dn: dn, nuevo: nuevo, initial: initial },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
                    alert('INCORRECTO');
                }
                if (data[0].success == "YES") {
                    alert('CAMBIO CORRECTO');
                } else {
                    alert(data[0].success);
                }
            }
        });
    }
}

function UValn(dn,value) {
    alert('boo!'+dn+value);
}

function SelCelOfi(dn,value) {
    //alert('boo!'+dn+value);
    var e = document.getElementById("val-oficina");
    if (e) {
        var multi = e.options[e.selectedIndex].value;
        if (multi == "SELECCIONE") {
            alert('Selecciona una oficina Mcenzie!!!');
        }
    }
    $.ajax({    
        type: "POST",
        url: 'php/GetCellAvailableTag.php',
        data: { ofi: multi },
        dataType: "json",
        success: function(data) {
            if (data[0].success == "NO") {
                alert('INCORRECTO');
            }
            if (data[0].success == "YES") {
                $('#val-newtag').val(data[0].tag);
            } else {
                alert(data[0].success);
            }
        }
    });    
}


function UValnn(dn,value) {
    alert('boo!'+dn+value);
}

function UVal(dn,value) {
    var Tagedit="#edit-"+value;
    var Tagval="#val-"+value;
    var ButTxt = "Asigna";
    var but='<button type="button" class="btn btn-primary btn-sm" id="SvalBut" onclick="SVal('+"'"+value+"'"+')">'+ButTxt+'</button>';
    if (value == 'lanip') {
        var viplan = document.getElementById("validlanip").value;
        //alert(viplan);
        if (viplan == "NO") {
            ButTxt = 'Genera';
            var but='<button type="button" class="btn btn-primary btn-sm" id="SvalBut" onclick="SVal('+"'"+value+"'"+')">'+ButTxt+'</button>';
        } else {
            ButTxt = 'Elimina';
            var valueiplan = document.getElementById("lanipval").value;
            var but='<button type="button" class="btn btn-primary btn-sm" id="SvalBut" onclick="DelIPVal('+"'"+value+"'"+','+"'"+valueiplan+"'"+')">'+ButTxt+'</button>';
        }
    }
    if (value == 'servicios') {
        alert (value);
    }

    //alert(but);
    $(Tagedit).html(but);
    $(Tagval).prop("readonly", false);
    if (value == 'lanip') {
        $(Tagval).prop("readonly", true);
    }
}

function DelIPVal(value,ip) {
    // 140.100
    var ip=document.getElementById('lanipval').value;
    var eldn=document.getElementById('eldn').value;
    //alert(value+' -> '+ip);
    $.ajax({
        type: "POST",
        url: 'php/DeleteIP.php',
        data: { ip: ip, eldn: eldn },
        dataType: "json",
        success: function(data) {
            if (data[0].success == "YES") {
                alert('CORRECTO!');
                $('#val-lanip').val('IP BORRADO');
                $('#edit-lanip').html(data[0].lapiz);
                $('#validlanip').val('NO');

                alert('IP BORRADO');
            } else {
                alert(data[0].success);
            }
        }
    });
}

function SVal(value) {
    var Tagval="val-"+value;
    var dn=document.getElementById('eldn').value;
    var nvalue=document.getElementById(Tagval).value;
    var ipasignado='NO';
    if (value == 'lanip') {
        // Revisar si ya definimos checkbox
        var e = document.getElementById("seleredes");
        if (e) {
            var multi = e.options[e.selectedIndex].value;
            if (multi == "SELECCIONE") {
                alert('Selecciona una red!!!');
            }
        } else {
            var multi = 'DUNNO';
        }
        var valueofi=document.getElementById('val-oficina').value;
        var assigned = 'DUNNO';
        $.ajax({
                type: "POST",
                url: 'php/GenerateIP.php',
                data: { dn: dn, value: value, valueofi: valueofi, multi: multi },
                dataType: "json",
                async: false,
                success: function(data) {
                    if (data[0].success == "YES") {
                        assigned=data[0].asignado;
                        if (data[0].multi == "YES") {
                            alert('El departamento asignado al usuario tiene varios segmentos de red disponibles, seleccione el segmento de red en donde darlo de alta');
                            $('#selevale').html(data[0].sele);
                            document.getElementById("SvalBut").disabled = true;
                        }
                        nvalue=data[0].newip;
                        if (data[0].asignado == "SI") {
                            $('#val-lanip').val(data[0].newip);
                            alert('IP Asignado:'+data[0].newip);
                        }
                    } else {
                        alert(data[0].success);
                    }

               }
        });
        if (assigned == "NO") {
            alert ('IP NO ASIGNADO AUN');
            return false;
        } else {
            document.getElementById("val-lanip").value = nvalue;
        }
    }
    //Crear lapiz para ip al salvar MAC
    if (value == 'lanmac') {
        var lapiziplan='<a href="#" onclick="UVal('+"'"+dn+"'"+','+"'"+'lanip'+"'"+')"><span class="fa fa-pencil"></span></a>';
        var Tagelan="#edit-lanip";
        $(Tagelan).html(lapiziplan);
    }
    if (value == 'wifimac') {
        var Tagewlan="#val-wifiip";
        $(Tagewlan).html('aqui se mostrara la ip calculada');
    }

    if (value == 'SAMBA') {
        alert('samba!');
    }
    $.ajax({
            type: "POST",
            url: 'php/UpdateLDAPvalue.php',
            data: { dn: dn, nvalue: nvalue, value: value },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
                alert('INCORRECTO');
            }
                if (data[0].success == "YES") {
                    alert('CORRECTO');
                    var Tagedit="#edit-"+value;
                    var Tagval="#val-"+value;
                    var but='<a href="#" onclick="UVal('+"'"+dn+"'"+','+"'"+value+"'"+')"><span class="fa fa-pencil"></span></a>';
                    $(Tagedit).html(but);
                    $(Tagval).prop("readonly", true);
                    if (value == 'lanmac') {
                        alert('Ya puede asignar la direccion IP LAN, pulse el lapiz y de click en Generar');
                    }
                } else {
                    alert(data[0].success);
                }
           }
       });
}

function DelUserFromGroup(value,grupo,indice) {
    var r = confirm('Desea borrar a '+value+' del grupo '+grupo+' ?');
    if (r == true) {
        $.ajax({
                type: "POST",
                url: 'php/DeleteLDAPvalue.php',
                data: { dn: grupo, nvalue: value , value: "member" },
                dataType: "json",
                beforeSend: function() {
                    $('#TOPDIV').html('');
                    $("#loaderDiv").show();
                },
                success: function(data) {
                    $("#loaderDiv").hide();
                    if (data[0].success == "NO") {
                    alert('INCORRECTO');
                }
                    if (data[0].success == "YES") {
                        alert('CORRECTO');
                    } else {
                        alert(data[0].success);
                    }

               }
           });
    } else {
      alert('Accion cancelada');
    }
}

function AddUserToGroup(value,grupo,indice) {
    var tag = '#CapturaNUser'+indice;
    $(tag).html('<input type="text" id="addlus"><div id="2suggesstion-box">v</div>');
    $("#addlus").keyup(function() {
    $.ajax({
      type: "POST",
      url: "php/searchusergrp.php",
      data: 'keyword=' + $(this).val(),
      beforeSend: function() {
        $("#addlus").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
      },
      success: function(data) {
        $("#2suggesstion-box").show();
        $("#2suggesstion-box").html(data);
        $("#addlus").css("background", "#FFF");
      }
    });
  });

}

function DelAlias(nvalue) {
    var dn=document.getElementById('eldn').value;
    var value="aliascuentagoogle";
    $.ajax({
            type: "POST",
            url: 'php/DeleteLDAPvalue.php',
            data: { dn: dn, nvalue: nvalue, value: value },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
                alert('INCORRECTO');
            }
                if (data[0].success == "YES") {
                    alert('CORRECTO');
                } else {
                    alert('Succ! '+data[0].success);
                }

           }
       });
}

function AddAlias() {
    var nvalue=document.getElementById('newvalue').value;
    var dn=document.getElementById('eldn').value;
    var value="aliascuentagoogle";
    //alert(nvalue+dn);
    $.ajax({
            type: "POST",
            url: 'php/UpdateLDAPvalue.php',
            data: { dn: dn, nvalue: nvalue, value: value },
            dataType: "json",
            beforeSend: function() {
                $('#TOPDIV').html('');
                $("#loaderDiv").show();
            },
            success: function(data) {
                $("#loaderDiv").hide();
                if (data[0].success == "NO") {
                alert('INCORRECTO');
            }
                if (data[0].success == "YES") {
                    alert('CORRECTO');
                } else {
                    alert(data[0].success);
                }

           }
       });
}

function validarinput(tipo,valor,chkexist) {
    var va=document.getElementById('val-'+valor).value;
    if (tipo == 'palabra') {
        var re = new RegExp("^([a-zA-Z]+)$");
        var err = "Solo letras";
    }
    if (tipo == 'palabrasp') {
        var re = new RegExp("^[a-zA-Z]([\\s]|[a-zA-Z])+[a-zA-Z]$");
        var err = "Solo letras y Espacios";
    }
    if (tipo == 'palabraspforce') {
        var re = new RegExp("^([a-zA-Z]+)([\\s])([\\s]|[a-zA-Z])+[a-zA-Z]$");
        var err = "Solo letras y Espacios, DOS Palabras";
    }
    if (tipo == 'numero') {
        var re = new RegExp("^[\\d]+$");
        var err = "Solo numeros";
    }
    if (tipo == 'mac') {
        var re = new RegExp("^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$");
        var err = "11:22:33:44:55";
    }

    // Validar que no existe en deviceusers
    if (valor == "uid") {
        $.ajax({
            type: "POST",
            url: 'php/ValidateExistentDeviceUser.php',
            data: { what: valor, value: va },
            dataType: "json",
            async: false,
            success: function(data) {
                if (data[0].success == "NO") {
                    //alert(data[0].err);
                    alert('USUARIO EXISTE EN DEVICE USERS, DESHABILITANDO EL GUARDAR, CORRIJA E INTENTE DE NUEVO');
                    document.getElementById("BtnSaveNewUser").disabled = true;
                } else {
                    //$('#BtnSaveNewUser').prop('disabled', 'false');
                }
                if (data[0].success == "YES") {
                    //alert('USUARIO NO EXISTE EN DEVICE USERS');
                }
            }
        });
    }

    if (tipo == 'validadeviceuser') {
        $.ajax({
            type: "POST",
            url: 'php/ValidaNewDeviceUser.php',
            data: { what: valor, value: va },
            dataType: "json",
            async: false,
            success: function(data) {
                if (data[0].success == "NO") {
                    alert(data[0].err);
                } else {
                    //$('#BtnSaveNewUser').prop('disabled', 'false');
                }
                if (data[0].success == "YES") {
                    //alert('USUARIO NO EXISTE');
                }
            }
        });
    }
    if (re.test(va)) {
        var exist = "DUNNO";
        if (chkexist == "SI") {
            $.ajax({
                    type: "POST",
                    url: 'php/CheckExistentValueLDAP.php',
                    data: { what: valor, value: va },
                    dataType: "json",
                    async: false,
                    beforeSend: function() {
                        $('#TOPDIV').html('');
                        $("#loaderDiv").show();
                    },
                    success: function(data) {
                        $("#loaderDiv").hide();
                        if (data[0].success == "NO") {
                            alert(valor+' '+va+' EXISTE');
                            $('#val-uid').html('');
                            exist="YES";
                        } else {
                            //$('#BtnSaveNewUser').prop('disabled', 'false');
                        }
                        if (data[0].success == "YES") {
                            //alert('USUARIO NO EXISTE');
                        }
                   }
            });
        }
        console.log("Valid");
        if ((valor == "dunumeroempleado")&&(exist != "YES")) {
            alert('NUMERO DE EMPLEADO NO EXISTE, CREANDO NUEVO REGISTRO');
            $('#val-duusernname').prop('readonly', false);
            $('#val-dunombre').prop('readonly', false);
            $('#val-duoficina').prop('readonly', false);
        }
        if ((valor == "dunumeroempleado")&&(exist == "YES")) {
            alert('NUMERO DE EMPLEADO EXISTE, TOMANDO INFORMACION DE USERS');
            $.ajax({
                type: "POST",
                url: 'php/GetNoEmpInfoFromLDAP.php',
                data: { valor: va },
                dataType: "json",
                success: function(data) {
                    if (data[0].success == "YES") {
                        $("#val-duusernname").val(data[0].uid);
                        $("#val-dunombre").val(data[0].cn);
                        $("#val-duoficina").val(data[0].oficina);
                    }
                }
            });
            
        }
        if ((valor == "uid")&&(exist != "YES")) {
            $("#val-mail").val(va+'@tpitic.com.mx');
            $.ajax({
                type: "POST",
                url: 'php/ChkOCSTag.php',
                data: { valor: va },
                dataType: "json",
                success: function(data) {
                    if (data[0].success == "YES") {
                        if (data[0].valor == "NO") {
                            alert('TAG OCS NO ENCONTRADO');
                        } else {
                            alert('TAG OCS PARA '+va+' ENCONTRADO: '+data[0].valor);
                        }
                        $('#lanmacsel').html(data[0].lanmac);
                        $('#wifimacsel').html(data[0].wifimac);
                        $('#wifimac-sel').prop('disabled', 'disabled');
                        $('#lanmac-BtnMacChn').prop('disabled', 'disabled');
                        $('#wifimac-BtnMacChn').prop('disabled', 'disabled');
                    }
                }
            });
        }
        var selectedofi = $( "#val-oficina" ).val();
        if ((valor == "lanmac")||(valor == "wifimac")) {
            //$("#val-mail").val(va+'@tpitic.com.mx');
            if ($( "#val-oficina" ).val() == "SELECCIONE") {
                alert( "ELIJA LA OFICINA" );
                $("#val-lanmac").val('');
            } else {
                CalcularIP(selectedofi,valor);
            }
        }


    } else {
        console.log("Invalid");
        alert('Entrada invalida ('+err+')');
    }
}

// CalcularIP(oficina,lanmac o wifimac)
function CalcularIP(ofi,valor) {
    alert('Calculando ip '+ofi+'->'+valor);
    if (valor == "wifimac") {
        $("#val-wifiip").val('N/A');
        $('#val-wifiip').attr('readonly', true);
        return false;
    }
    if(ofi == 666) {
        var e = document.getElementById("seleredes");
        var multi = e.options[e.selectedIndex].value;
        ofi=multi;
    }
    $.ajax({
        type: "POST",
        url: 'php/GetAvIPForOficina.php',
        data: { ofi: ofi, valor: valor },
        dataType: "json",
        success: function(data) {
            if (data[0].success == "YES") {
                nvalue=data[0].nvalue;
                alert ("Nueva IP "+valor+" de la oficina "+ofi+" generada para usuario: "+nvalue);
                if (valor == "lanmac") {
                    $("#val-lanip").val(nvalue);
                    $('#val-lanip').attr('readonly', true);
                    $('#wifimac-sel').prop('disabled', false);
                }
                if (valor == "wifimac") {
                    $("#val-wifiip").val(nvalue);
                    $('#val-wifiip').attr('readonly', true);
                }
            }
            if (data[0].success == "MULTI") {
                //alert(data[0].sele);
                $('#selnetdiv').html(data[0].sele);
                alert('El departamento que intenta registrar tiene varias redes, seleccione la red del nuevo usuario');
            }
        }
    });
}

function SelectNetSegment() {
   var e = document.getElementById("seleredes");
    var multi = e.options[e.selectedIndex].value;
    alert('Red multiple= '+multi);
}

function SaveNewUser() {
    var data = $("#newuser").serializeArray();
    //alert(data);
    $.ajax({
        type: "POST",
        url: 'php/SaveNewUser.php',
        data: { data: data },
        dataType: "json",
        async: false,
        success: function(data) {
            if (data[0].success == "YES") {
                alert('Usuario Guardado');
            } else {
                alert(data[0].success);
            }
        }
    });
}

function SaveNewCell() {
    var data = $("#newcell").serializeArray();
    $.ajax({
        type: "POST",
        url: 'php/SaveNewCell.php',
        data: { data: data },
        dataType: "json",
        async: false,
        success: function(data) {
            if (data[0].success == "YES") {
                alert('Usuario Guardado');
            } else {
                alert(data[0].success);
            }
        }
    });
}

function SaveNewDevUser() {
    var data = $("#newdevuser").serializeArray();
    $.ajax({
        type: "POST",
        url: 'php/SaveNewDevUser.php',
        data: { data: data },
        dataType: "json",
        async: false,
        success: function(data) {
            if (data[0].success == "YES") {
                alert('Usuario Guardado');
            } else {
                alert(data[0].success);
            }
        }
    });
}

function ValidateMacSet(tipo) {
    var e = document.getElementById(tipo+"-sel");
    if (e) {
        var multi = e.options[e.selectedIndex].value;
        if (multi == "NO") {
            $( "#val-"+tipo ).prop( "readonly", true );
            $( "#val-"+tipo ).attr('value', 'NO');
        }
        if (multi == "SELECCIONE") {
            alert('Selecciona algo con una chingada!!!');
            $( "#val-"+tipo ).prop( "readonly", true );
            $( "#val-"+tipo ).attr('value','');
        }
        if (multi == "MANUAL") {
            $( "#val-"+tipo ).prop( "readonly", false );
            $( "#val-"+tipo ).attr('value','');
        }
        var re = new RegExp("^([0-9a-fA-F]{2}[:.-]){5}[0-9a-fA-F]{2}$");
        if (re.test(multi)) {
            var selectedofi = $( "#val-oficina" ).val();
            if ($( "#val-oficina" ).val() == "SELECCIONE") {
                alert( "ELIJA LA OFICINA" );
                $("#"+tipo+"-sel").val("SELECCIONE");
                return false;
            }
            $( "#val-"+tipo ).attr('value', 'Definido con valor OCS');
            $( "#val-"+tipo ).prop( "readonly", true );
            CalcularIP(selectedofi,tipo);
            // calcular ip aki
        }

        //alert (multi);
    } else {
        //alert ('No Existe Check de redes');
        var multi = 'DUNNO';
    }
}

function GetComment() {
    $.ajax({
        type: "POST",
        url: 'php/GetComment.php',
        dataType: "json",
        success: function(data) {
            //alert(data[0].success);
            if (data[0].success == "YES") {
                $('#NewLDAPUser').html(data[0].data);
                //$('#celltable').dataTable();
                $('#celltable').dataTable( { "lengthMenu": [[150, -1], [150, "All"]]  } );
                //alert(data[0].data);
            }
        }
    });
}

function SaveMacChange(tipo) {
    if (tipo == "wifimac") {
        // Revisar que este declarada el valor de la MAC LAN
        var lmvalc = document.getElementById("val-lanmac").value;
        if (lmvalc == "NO EXISTE MAC LAN EN LDAP") {
            alert('Declare primero el valor de la LAN MAC antes de continuar con la MAC Wifi');
            return false;
        }
    }
    if (tipo == "lanmac") {
        var lanip=document.getElementById('val-lanip').value;
    } else {
        var lanip='DUNNO';
    }
    var e = document.getElementById(tipo+"-sel");
    var multi = e.options[e.selectedIndex].value;
    var v=document.getElementById('val-'+tipo).value;
    var va = v.toUpperCase();
    if (va == "DEFINIDO CON VALOR OCS") {
        vxa=multi;
        alert(vxa);
        va = vxa.toUpperCase();
    }
    var dn=document.getElementById('eldn').value;
    var ofi=document.getElementById('val-oficina').value;
    var re = new RegExp("^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$");
    if (re.test(va)) {
        console.log("Valid Mac");
    } else {
        if (va != "NO") {
            alert("INVALID MAC VALUE, este mensaje no aplica si se escogio la mac desde el combo de OCS, si ves este mensaje, por favor toma un screenshot y mandalo a jferia");
        }
    }
    $.ajax({
        type: "POST",
        url: 'php/UpdateMacAddress.php',
        data: { dn: dn, va: va, multi: multi, ofi: ofi, tipo: tipo, lanip: lanip },
        dataType: "json",
        async: false,
        success: function(data) {
            
            if (data[0].success == "YES") {
                var tag="#"+tipo+"-netscombo";
                $(tag).html(data[0].sele);
            }
            if (data[0].mcok == "OK") {
                alert('Cambio Mac OK');
            }
        }
    });
}