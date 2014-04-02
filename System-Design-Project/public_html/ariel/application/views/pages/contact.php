
<h2>Thank you for your interest in SUNY TECH. </h2>
<h2>We look forward to hearing from you.</h2>
<div id="contactform">

    <td style="50%">
        <font color=#1346AD size=+2>Survey Form</font>
        <hr></hr>
        <form name="htmlform" action="html_form_send.php" method="post">
            <input type=hidden name=AUTONUMBER></input>
            <input type=hidden name=FILETYPE value=TEXT></input>
            <input type=hidden name=FIELDDELIM value=TAB></input>
            <input type=hidden name=FILELOCATION value="/temp/survey.txt"></input>
            <input type=hidden name=REDIRECT value=""></input>
            <input type=hidden name=REDIRECTTIME value=4></input>
            <input type=hidden name=FORMVARSTOFILE value=></input>
                <tr>
                   <p> <td valign="top">
                        <label for="first_name">First Name *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="first_name" maxlength="50" size="30">
                    </td>
                </tr></p>

                <tr><p>
                    <td valign="top"">
                    <label for="last_name">Last Name *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="last_name" maxlength="50" size="30">
                    </td>
                </tr></p>
                <tr><p>
                    <td valign="top">
                        <label for="email">Email Address *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="email" maxlength="80" size="30">
                    </td>
                </tr></p>
                <tr><p>
                    <td valign="top">
                        <label for="telephone">Telephone Number</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="telephone" maxlength="30" size="30">
                    </td>
                </tr></p>
                <tr><p>
                    <td valign="top">
                        <label for="comments">Comments *</label>
                    </td>
                    <td valign="top">
                        <textarea  name="comments" maxlength="1000" cols="25" rows="6"></textarea>
                    </td>
                </tr></p>
            <p>Gender:<br/>
                <input type=radio name=Gender value=Male>Male<br/></input>
                <input type=radio name=Gender value=Female>Female<br/></input>
            <p>

                Our service Rating:1(bad)-5(perfect)</p>
            <select>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select><br></br>
            <p>Any other Comments:</p>
            <textarea cols=40 rows=3 name=textBox4></textarea>
            <hr></hr>
            <input type=submit value=Submit></input><input type=reset value=Clear></input><br/>
        </form>
    </td>
    </tr>
    </tbody>
    </table>
</div>