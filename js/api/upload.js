(function($, w, d) {

  /**
   * @class
   */
  w.Wistia = function() {};
  
  /**
   * The project key.
   * @type  {String}
   */
  Wistia.project = typeof $wp !== 'undefined' ? $wp : 0;

  /**
   * The Upload API key.
   * @type  {String}
   */
  Wistia.apikey  = typeof $wk !== 'undefined' ? $wk : 0;

  /**
   * Process an upload.
   *
   * @param  {Object}  args                The parameter list.
   * @param  {File}    args.file           A selector to a file input field.
   * @param  {String}  args.title          The video's title.
   * @param  {String}  [args.description]  A description for the video.
   */
  Wistia.upload = function(args) {
    // Check arguments
    if (typeof args != 'object') return Wistia.error('Invalid or missing argument object');
    if (!args.file) return Wistia.error('No file specified');
    if (!(args.file instanceof File)) return Wistia.error('Invalid file object');
    if (!args.title) return Wistia.error('No video title specified');

    // Check keys
    if (!Wistia.apikey) return Wistia.error('No valid API key found');
    if (!Wistia.project) return Wistia.error('No valid project key found');

    // Build payload
    var payload = new FormData();
    payload.append('project_id', Wistia.project);
    payload.append('api_password', Wistia.apikey);
    payload.append('file', args.file);
    payload.append('name', args.title);
    if (args.description)
      payload.append('description', args.description);

    // Trigger an "upload started" event
    var e = $.Event('wistia.upload.started', {
      file: args.file
    });
    $(w).trigger(e);

    // Send POST request
    $.ajax({
      method: 'POST',
      url: 'https://upload.wistia.com/',
      dataType: 'json',
      crossDomain: true,
      processData: false,
      contentType: false,
      cache: false,
      data: payload,
      success: function(responseData) {
        var e = $.Event('wistia.upload.complete', {
          apiResponse: responseData,
          uploadedFile: args.file
        });
        $(w).trigger(e);
      },
      error: function(xhr, status, httpError) {
        var e = $.Event('wistia.upload.failed', {
          status: status,
          xhr: xhr
        });
        $(w).trigger(e);
      }
    });

    return true;
  };

  /**
   * Log an error without cancelling other script executions.
   *
   * @param   {String}  msg  The error message.
   * @return  {bool}         False, always.
   */
  Wistia.error = function(msg) {
    console.error('Wistia uploader: '+ msg);
    return false;
  };

})(jQuery, window, document);
