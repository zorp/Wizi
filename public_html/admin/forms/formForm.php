<script type="text/javascript">
function clearConfirmationPage(){
	document.my_form.confirmation_page.value = '';
	document.my_form.confirmation_page_name.value = '';
}
</script>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr> 
                        <td bgcolor="#FFFFFF" colspan="3"><img src="../graphics/transp.gif" height="20"></td>
                      </tr> 
                      <tr>
                        <td class="header"><?=( $action =='add')?'Create form':'Edit form'?> '<?=$forms->name?>'</td>
                      </tr> 
                        <td bgcolor="#FFFFFF" class="save_message"><?=$msg?></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="plainText">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
                      <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="tdpadtext">Name</td>
                                </tr>
                                <tr>
                                    <td class="tdpadtext">
                                      <input type="text" name="name" class="input"  value="<?=$forms->name?>">
                                    </td>
                               </tr>
                                <tr>
                                    <td class="tdpadtext">Action type</td>
                                </tr>
                                <tr>
                                    <td class="tdpadtext">
                                      <select name="action_type" class="input" onchange="document.my_form.submit()">
                                        <option value="db" <?=( $forms->action_type == 'db')?'selected':''?>>Save in database</option>
																				<option value="mail" <?=( $forms->action_type == 'mail')?'selected':''?>>Mail</option>
                                        <option value="csv" <?=( $forms->action_type == 'csv')?'selected':''?>>CSV log file</option>
																				<option value="newsletter" <?=( $forms->action_type == 'newsletter')?'selected':''?>>Mailinglist</option>
                                        <option value="custom" <?=( $forms->action_type == 'custom')?'selected':''?>>Custom action</option>
                                      </select>
                                    </td>
                               </tr>
                                <? if( $forms->action_type == 'mail' ):?>
                                  <tr>
                                      <td class="tdpadtext">Mail recipients <span style="font-weight:normal;color:#666666">[separated by comma]</span></td>
                                  </tr>
                                  <tr>
                                      <td class="tdpadtext">
                                        <input type="text" name="mail_recipients" class="input"  value="<?= $forms->mail_recipients?>">
                                      </td>
                                 </tr>
                                <? endif?>
                                <? if( $forms->action_type == 'custom' ):?>
                                  <tr>
                                      <td class="tdpadtext">Extern url for custom forms processing</td>
                                  </tr>
                                  <tr>
                                      <td class="tdpadtext">
                                        <input type="text" name="extern_url" class="input"  value="<?=$forms->extern_url?>">
                                      </td>
                                 </tr>
                                <? endif?>
                                <tr>
                                    <td class="tdpadtext">Confirmation page</td>
                                </tr>
                                <tr>
                                    <td class="tdpadtext">
                                      <input type="hidden" name="confirmation_page" value="<?=$forms->confirmation_page?>">
                                      <input type="text" name="confirmation_page_name" class="input" style="width:200px" value="<?=$forms->confirmation_page_name?>">
                                      <input type="button" onclick="selectPage()" value="Select page" class="medium_knap">
																			<input type="button" onclick="clearConfirmationPage()" value="Clear input" class="knapred">
                                    </td>
                               </tr>
                                <tr>
                                    <td class="tdpadtext">Submit label</td>
                                </tr>
                                <tr>
                                    <td class="tdpadtext">
                                      <input type="text" name="submit_label" class="input"  value="<?=$forms->submit_label?>">
                                    </td>
                               </tr>
                                <tr>
                                    <td class="tdpadtext">Cancel label</td>
                                </tr>
                                <tr>
                                    <td class="tdpadtext">
                                      <input type="text" name="cancel_label" class="input"  value="<?=$forms->cancel_label?>">
                                    </td>
                               </tr>
                               <tr><td class="tdpadtext"><img src="../graphics/transp.gif" height="20"></td> </tr>
                              </table>
                          </td>
                        </tr>
                   </table>
                     <? if( $id ):?>
                     <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
                       <tr>
                        <td colspan="5" class="tdpadtext">Form fields</td>
                       </tr>
                       <?
                           $list  = $fields->getFields();
                           $n = count( $list );
                           $typelist = $fields->getTypeList();
                           $m = count( $typelist );
                       ?>
                        <? for( $i = 0; $i< $n; $i++ ):?>
                          <tr class="<?=($i%2==0)?"color2":"color3"?>" style="padding-top:3px;padding-bottom:3px;">
                            <td>
                              <a href="field.php?id=<?=$list[$i]["id"]?>&formid=<?=$id?>" class="tabelText"><?=$list[$i]["title"]?></a>
                            </td>
                            <td>
                              <a href="field.php?id=<?=$list[$i]["id"]?>&formid=<?=$id?>" class="tabelText">
                              <?=substr( $list[$i]["label"],0,100 )?><?=( strlen( $list[$i]["label"]) > 100 )?'...':''?></a>
                            </td>
                            <td valign="middle">
                              <!--pil up-->
															<? if( $i != 0 ):?>
																<a href="#" onclick="movingUp( <?=$list[$i]["id"]?> )"><img src="../graphics/pil_up.gif" alt="Move up" border="0"></a>
															<? else:?>
																<img src="../graphics/transp.gif" width="11" height="9">
															<? endif?>
                              <!--pil down-->
															<? if( $i != ( count( $list ) - 1 ) ):?>
																<a href="#" onclick="movingDown( <?=$list[$i]["id"]?> )"><img src="../graphics/pil_down.gif" alt="Move down" border="0"></a>
															<? else:?>
																<img src="../graphics/transp.gif" width="11" height="9">
															<? endif?>
                            </td>
                            <td>
                              <? if ( $forms->action_type == 'newsletter' && $list[$i]["title"] == 'Mail field' ):?>
																&nbsp;
															<? else: ?>
																<a href="#" onclick="removing(<?=$list[$i]["id"]?>)" class="redlink">Delete</a>
															<? endif?>
                            </td>
                            <td> 
                              <a href="field.php?id=<?=$list[$i]["id"]?>&formid=<?=$id?>" class="greenlink">Edit</a> 
                            </td>
                          </tr>
                        <? endfor?>
                        <tr>
                          <td colspan="5" class="tabelText" style="padding-right:25px;padding-top:5px;padding-bottom:25px">
                                <select style="width:200px" name="typelist">
                                  <? for( $j = 0; $j < $m; $j++ ):?>
                                    <? if ($forms->action_type == 'newsletter'):?>
																			<? if ($typelist[$j]["title"] != 'Mail field'):?>
																				<option value="<?=$typelist[$j]["id"]?>"><?=$typelist[$j]["title"]?></option>
																			<? endif?>
																		<? else:?>
																			<option value="<?=$typelist[$j]["id"]?>"><?=$typelist[$j]["title"]?></option>
																		<? endif?>
                                  <? endfor?>
                                </select>
                                <input type="button" value="Add field" onclick="addfield()" class="medium_knap">
                          </td>
                        </tr>
                        <tr><td colspan="5" class="tdpadtext"><img src="../graphics/transp.gif" height="20"></td> </tr>

                     </table>
                     <? endif?>
                    </table>    
                   <table width="310" cellpadding="0" cellspacing="0" border="0">
                      <tr> 
                        <td class="tdpadtext">&nbsp;
                          
                        </td>
                      </tr>
                      <tr>
                        <td class="tdpadtext">
													<? if( $referer ):?>
								  	  		  <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
													<? endif?>
												</td>
                        <td align="right">
                          <input type="button" value="Cancel" onclick="document.location.href='index.php?pane=forms'" class="knapred"> <input type="submit" value="Save" name="submited" class="knapgreen">
                        </td>
                      </tr>
                   </table>
                   <br />