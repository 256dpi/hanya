// Highlight Definitions
CodeMirror.defineMode("hanyaMeta", function(config, parserConfig) {
  var hanyaMetaOverlay = {
    token: function(stream, state) {
      if (stream.match("//--") || stream.match("--//")) {
        return "hanyaMeta";
      }
      while (stream.next() != null && !stream.match("/", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "text/html"), hanyaMetaOverlay);
});

// Highlight Definitions
CodeMirror.defineMode("hanyaDef", function(config, parserConfig) {
  var hanyaDefOverlay = {
    token: function(stream, state) {
      if (stream.match("[")) {
        while ((ch = stream.next()) != null)
          if (ch == "]" && stream.next() != null) break;
        return "hanyaDef";
      }
      while (stream.next() != null && !stream.match("[", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "hanyaMeta"), hanyaDefOverlay);
});

// Higlight Tags
CodeMirror.defineMode("hanyaTag", function(config, parserConfig) {
  var hanyaTagOverlay = {
    token: function(stream, state) {
      if (stream.match("{")) {
        while ((ch = stream.next()) != null)
          if (ch == ")" && stream.next() == "}") break;
        return "hanyaTag";
      }
      while (stream.next() != null && !stream.match("{", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "hanyaDef"), hanyaTagOverlay);
});

// Higlight Replacements
CodeMirror.defineMode("hanyaRet", function(config, parserConfig) {
  var hanyaRepOverlay = {
    token: function(stream, state) {
      if (stream.match("#")) {
        while ((ch = stream.next()) != null)
          if (ch == "}") break;
        return "hanyaRet";
      }
      while (stream.next() != null && !stream.match("{", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "hanyaTag"), hanyaRepOverlay);
});

// Higlight Vars
CodeMirror.defineMode("hanyaVar", function(config, parserConfig) {
  var hanyaVarOverlay = {
    token: function(stream, state) {
      if (stream.match("$")) {
        while ((ch = stream.next()) != null)
          if (ch == ")") break;
        return "hanyaVar";
      }
      while (stream.next() != null && !stream.match("$", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "hanyaRet"), hanyaVarOverlay);
});