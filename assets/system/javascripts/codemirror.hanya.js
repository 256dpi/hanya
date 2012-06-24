/* HTML ENHANCEMENTS */

// Highlight Meta
CodeMirror.defineMode("hanya-meta", function(config, parserConfig) {
  var hanyaOverlay = {
    token: function(stream, state) {
      if (stream.match("//--") || stream.match("--//")) {
        return "hanya-meta";
      }
      while (stream.next() != null && !stream.match("/", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "text/html"), hanyaOverlay);
});

// Highlight Definitions
CodeMirror.defineMode("hanya-def", function(config, parserConfig) {
  var hanyaOverlay = {
    token: function(stream, state) {
      if (stream.match("[")) {
        while ((ch = stream.next()) != null)
          if (ch == "]" && stream.next() != null) break;
        return "hanya-def";
      }
      while (stream.next() != null && !stream.match("[", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "hanya-meta"), hanyaOverlay);
});

// Higlight Tags
CodeMirror.defineMode("hanya-tag", function(config, parserConfig) {
  var hanyaOverlay = {
    token: function(stream, state) {
      if (stream.match("{")) {
        while ((ch = stream.next()) != null)
          if (ch == ")" && stream.next() == "}") break;
        return "hanya-tag";
      }
      while (stream.next() != null && !stream.match("{", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "hanya-def"), hanyaOverlay);
});

// Higlight Vars
CodeMirror.defineMode("hanya-var", function(config, parserConfig) {
  var hanyaOverlay = {
    token: function(stream, state) {
      if (stream.match("$")) {
        while ((ch = stream.next()) != null)
          if (ch == ")") break;
        return "hanya-var";
      }
      while (stream.next() != null && !stream.match("$", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "hanya-tag"), hanyaOverlay);
});

/* PROPERTIES ENHANCEMENT */

CodeMirror.defineMode("hanya-prop-var", function(config, parserConfig) {
  var hanyaOverlay = {
    token: function(stream, state) {
      var ch;
      if (stream.match("#{")) {
        while ((ch = stream.next()) != null)
          if (ch == "}") break;
        return "hanya-prop-var";
      }
      while (stream.next() != null && !stream.match("#{", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "properties"), hanyaOverlay);
});