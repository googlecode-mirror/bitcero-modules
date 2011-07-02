<div id="<?php echo $this->id(); ?>">
    <form action="products.php" method="POST" enctype="multipart/form-data">
        <div class="fileupload-buttonbar">
            <label class="fileinput-button">
                <span><?php _e('+ Files','shop'); ?></span>
                <input type="file" name="image" />
            </label>
            <button type="submit" class="start"><?php _e('Upload','shop'); ?></button>
            <button type="reset" class="cancel"><?php _e('Cancel','shop'); ?></button>
        </div>
        <input type="hidden" name="action" value="upload" />
    </form>
    <div class="fileupload-content">
        <table class="files" width="100%"></table>
        <div class="fileupload-progressbar"></div>
    </div>
</div>
<script id="<?php echo $this->id(); ?>-template-upload" type="text/x-jquery-tmpl">
    <tr class="template-upload{{if error}} ui-state-error{{/if}}">
        <td>
            <div class="preview"></div>
            <div>
            <span class="name">${name}</span>
            <span class="size">${sizef}</span>
            </div>
            {{if error}}
            <span class="error"<?php _e('Error:','shop'); ?>
                    {{if error === 'maxFileSize'}}<?php _e('File is too big','shop'); ?>
                    
                    {{else error === 'minFileSize'}}<?php _e('File is too small','shop'); ?>
                    
                    {{else error === 'acceptFileTypes'}}<?php _e('Filetype not allowed','shop'); ?>
                    
                    {{else error === 'maxNumberOfFiles'}}<?php _e('Max number of files exceeded','shop'); ?>
                    
                    {{else}}${error}
                    {{/if}}
            </span>
            {{else}}
            <span class="progress" colspan="2"><div></div></span>
            <span class="start"><button><?php _e('Start','shop'); ?></button></span>
            {{/if}}
            <span class="cancel"><button><?php _e('Cancel','shop'); ?></button></span>
    </td>
    </tr>
</script>
<script id="<?php echo $this->id(); ?>-template-download" type="text/x-jquery-tmpl">
    <tr class="template-download{{if error}} ui-state-error{{/if}}">
        <td align="center">
        {{if error}}
            <div class="name">${name}<br />${sizef}</div>
            <div class="error" colspan="2">Error:
                {{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
                {{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
                {{else error === 3}}File was only partially uploaded
                {{else error === 4}}No File was uploaded
                {{else error === 5}}Missing a temporary folder
                {{else error === 6}}Failed to write file to disk
                {{else error === 7}}File upload stopped by extension
                {{else error === 'maxFileSize'}}File is too big
                {{else error === 'minFileSize'}}File is too small
                {{else error === 'acceptFileTypes'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
                {{else error === 'emptyResult'}}Empty file upload result
                {{else}}${error}
                {{/if}}
            </div>
        {{else}}
            <div class="preview">
                {{if thumbnail_url}}
                    <a href="${url}" target="_blank"><img src="${thumbnail_url}"></a>
                {{/if}}
            </div>
            <div class="name">
                <a href="${url}"{{if thumbnail_url}} target="_blank"{{/if}}>${name}</a>
            </div>
            <div class="size">${sizef}</div>
        {{/if}}
        <div class="delete"><button data-type="${delete_type}" data-url="${delete_url}">Delete</button></div>
        </td>
    </tr>
</script>