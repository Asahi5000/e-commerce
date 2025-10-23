    const dots = document.querySelectorAll(".timeline-dot"); 
    const title = document.getElementById("milestone-title"); 
    const text = document.getElementById("milestone-text"); 
    const image = document.getElementById("milestone-image"); 
    const milestones = { 
        1: { title: "Our Beginning", 
             text: "At Crimson Grand Drive, our journey began with a passion for redefining luxury on the road—bringing the world’s finest cars closer to enthusiasts who seek not just vehicles, but experiences of elegance, power, and prestige.", 
             image: "images/about1.jpg" }, 
        2: { title: "First Breakthrough", 
             text: "Our first breakthrough came when we transformed the way luxury cars were discovered and owned—introducing a seamless digital experience that blends exclusivity with convenience, making prestige accessible at every click.", 
             image: "images/about2.jpg" }, 
        3: { title: "Global Recognition", 
             text: "Earning global recognition, Crimson Grand Drive became a trusted name in luxury car e-commerce—celebrated for delivering unmatched quality, authenticity, and sophistication to discerning clients across continents.", 
             image: "images/about5.jpg" }, 
        4: { title: "Modern Era", 
             text: "In the modern era, Crimson Grand Drive continues to innovate—merging cutting-edge technology with timeless luxury to create an elevated car-buying experience tailored for today’s global lifestyle.", 
             image: "images/about4.jpg" } 
    }; 

    dots.forEach(dot => { dot.addEventListener("click", () => { 
        // Remove active class 
        dots.forEach(d => d.classList.remove("active")); 
        dot.classList.add("active"); 
        // Update content 
        const step = dot.dataset.step; 
        title.textContent = milestones[step].title; 
        text.textContent = milestones[step].text; 
        image.src = milestones[step].image; 
    }); 
}); 