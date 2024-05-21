function NumOfCon() {
    const numLinks = document.getElementById("num-contests").value;
  
    const container = document.getElementById("contest-labels");
  
    container.innerHTML = ""; 
  
    for (let i = 1; i <= numLinks; i++) {
      const newInput = document.createElement("input");
      newInput.type = "url";
      newInput.id = `link-${i}`; 
      newInput.placeholder = `Link ${i}`; 
      newInput.required = true;
  
      container.appendChild(newInput);
    }
  }