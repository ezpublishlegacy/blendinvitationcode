<div class="box-header">
    <h1 class="context-title">Create Invitation Codes</h1>
</div>
<form action={"/invitation/add"|ezurl} method="post">
    {if $complete}
    <p><strong>Codes Added</strong></p>
    <p>Copy and paste the list below into Excel for your records and delivery of codes.</p>
    {else}
    <p>Enter as many email addresses as you like, one per line. An invitation
    code will be generated for each address.</p>
    {/if}
    <textarea rows="15" name="emails" cols="80">{$emails}</textarea>
    <br />
    {if $complete|not}
    {*
    <input type="checkbox" name="SendInvitations" value="1" id="sendInvitation" />
    <label for="sendInvitation">Send Invitation Emails</label>
    *}
    <input type="submit" class="button" value="Create Codes" />
    {/if}
</form>