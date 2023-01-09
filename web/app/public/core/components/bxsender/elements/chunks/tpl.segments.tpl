{if count($segments) > 0}
{foreach $segments as $segment}
    <div class="checkbox checkbox-success">
        <label for="segments-{$segment['id']}">
            <input type="checkbox" class="segments" name="segments[]" id="segments-{$segment['id']}" value="{$segment['id']}" {if $segment['checked'] == 'checked'}checked{/if}>
            {$segment['name']}{if $segment['description']}{/if}
        </label>
    </div>
{/foreach}
{else}
У нас нет активных подписок
{/if}
