<div class="box-header">
<h1 class="context-title">Invitation Codes</h1>
</div>
{*
<form action={"/invitations/add"|ezurl} method="post">
    <textarea name="emails">
    </textarea>
    <input class="button" type="submit" value="Create Invitation Codes" />
</form>
*}
<form action={"/invitation/add"|ezurl} method="post">
    <p>
        <input class="button" type="submit" value="Create Invitation Codes" />
    </p>
</form>
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='invitation/list'
         item_count=$total_count
         view_parameters=$user_parameters
         item_limit=$per_page}
<table class="list">
    <tr>
        <th>&nbsp;</th>
        <th>Code</th>
        <th>Email</th>
        <th>User</th>
        <th>Created</th>
        <th>Accepted</th>
        <th>&nbsp;</th>
    </tr>
    {foreach $invitations as $invitation}
    <tr>
        <td>{*<input type="checkbox" />*}</td>
        <td>{concat($invitation.code|extract(0,4),'-',$invitation.code|extract(4,4),'-',$invitation.code|extract(8))}</td>
        <td>{$invitation.email}</td>
        <td>{if $invitation.user_id|not}Pending{else}
        {def $user=fetch('content','object',hash('object_id',$invitation.user_id))}
        {if $user}
        <a href={$user.main_node.url_alias|ezurl}>{$user.name}</a>
        {else}
        No User
        {/if}
        {/if}</td>
        <td>
            {if $invitation.created_at}
            {$invitation.created_at|datetime('custom','%M %j, %Y %h:%i %a')}
            {else}
            --
            {/if}
        </td>
        <td>
            {if $invitation.accepted_at}
            {$invitation.accepted_at|datetime('custom','%M %j, %Y %h:%i %a')}
            {else}
            --
            {/if}
        </td>
        <td>&nbsp;</td>
    </tr>
    {/foreach}
</table>
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='invitation/list'
         item_count=$total_count
         view_parameters=$user_parameters
         item_limit=$per_page}