<h2>IP-Binding</h2><br>

{if (isset($errorMessage) && ! empty($errorMessage))}
    <div class="row">
        <div class="alert alert-danger">
            {$errorMessage}
        </div>
    </div>
{/if}

{if (isset($successMessage) && ! empty($successMessage))}
    <div class="row">
        <div class="alert alert-success">
            {$successMessage}
        </div>
    </div>
{/if}

<div class="row" style="margin-bottom: 25px;">
    <div class="col-sm-12">
        <p>Das IP-Binding begrenzt die Lizenz auf die hinterlegte IP-Adresse, sodass die Lizenz nur auf dieser angegebenen IP-Adresse genutzt werden kann.</p>

        <form method="post" action="clientarea.php?action=productdetails&amp;id={$id}&license_action=change_binding">
            <input type="hidden" name="id" value="{$serviceid}" />
            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group">
                        <input type="text" name="address" class="form-control" placeholder="IP-Adresse" value="{$ipAddress}">
                        <span class="input-group-btn">
                            <button class="btn btn-success" type="submit">Speichern</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-sm-4">
        <a href="clientarea.php?action=productdetails&amp;id={$id}" class="btn btn-default btn-block">
            <i class="fa fa-arrow-circle-left"></i>
            Zurück zur Übersicht
        </a>
    </div>
</div>
